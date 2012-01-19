<?php

class Model_Art extends Model_Abstract_Meta
{
	const
		TAGME = 'prostavte_tegi',
		LOW_WIDTH = 800,
		LOW_HEIGHT = 600,
		LOW_RES = 'lowres',
		HIGH_WIDTH = 1920,
		HIGH_HEIGHT = 1080,
		HIGH_RES = 'highres',
		WALLPAPER = 'wallpaper';

	protected static $wallpaper_sizes = array(
		array(1152,864), array(1280,960),
		array(1400,1050), array(2048,1536),
		array(1024,768), array(1280,1024),
		array(1600,1200), array(1024,768),
		array(1280,1024), array(1600,1200),
		array(1280,800), array(1680,1050),
		array(1600,1200),
	);

	// Поля таблицы
	protected $fields = array(
		'id',
		'md5',
		'thumb',
		'extension',
		'resized',
		'animated',
		'author',
		'category',
		'tag',
		'rating',
		'translator',
		'source',
		'comment_count',
		'last_comment',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'art';

	// Последний номер similar пикчи
	protected $last_order = null;
	// Отметка о том, брали ли мы его уже из базы
	protected $last_order_known = false;

	public function insert() {

		if (
			!Check::is_hash($this->get('md5')) ||
			!Check::is_hash($this->get('thumb')) ||
			!$this->get('extension') ||
			Database::get_count('art', 'md5 = ?', $this->get('md5'))
		) {
			return $this;
		}

		if ($this->get('resized') == 1) {
			$this->calculate_resize();
		}

		$this->set('pretty_date', Transform_Text::rudate());
		$this->set('sortdate', ceil(microtime(true)*1000));
		if (!$this->get('area')) {
			$this->set('area', def::area(1));
		}

		$this->correct_tags();

		parent::insert();

		if (
			function_exists('puzzle_fill_cvec_from_file') &&
			function_exists('puzzle_compress_cvec')
		) {
			$imagelink = IMAGES.SL.'booru'.SL.'thumbs'.
				SL.'large_'.$this->get('thumb').'.jpg';

			$vector = puzzle_fill_cvec_from_file($imagelink);
			$vector = base64_encode(puzzle_compress_cvec($vector));

			Database::insert('art_similar', array(
				'id' => $this->get_id(),
				'vector' => $vector
			));
		}

		return $this;
	}

	protected function correct_tags() {
		$tags = $this->get('tag');
		$tags = explode('|', $tags);

		$tags = array_filter(array_unique($tags));

		if (empty($tags)) {
			$tags[] = self::TAGME;
		}

		$file = IMAGES.SL.'booru'.SL.'full'.SL.
			$this->get('md5').'.'.$this->get('extension');

		$sizes = getimagesize($file);
		$width = $sizes[0];
		$height = $sizes[1];

		if ($width < self::LOW_WIDTH && $height < self::LOW_HEIGHT) {
			$tags[] = self::LOW_RES;
		}
		if ($width >= self::HIGH_WIDTH && $height >= self::HIGH_HEIGHT) {
			$tags[] = self::HIGH_RES;
		}
		if (in_array(array($width, $height), self::$wallpaper_sizes)) {
			$tags[] = self::WALLPAPER;
		}

		$this->set('tag', '|' . implode('|', $tags) . '|');
	}

	public function add_similar($data) {
		if (!$this->last_order_known) {
			$this->last_order = Database::get_field('art_variation',
				'max(`order`)', 'art_id = ?', $this->get_id());

			$this->last_order_known = true;
		}

		if (is_numeric($this->last_order)) {
			$insert_order = $this->last_order + 1;
		} else {
			$insert_order = 0;
		}

		Database::insert('art_variation', array(
			'art_id' => $this->get_id(),
			'md5' => $data['md5'],
			'thumb' => $data['thumb'],
			'extension' => $data['extension'],
			'resized' => $data['resized'],
			'order' => $insert_order,
			'animated' => $data['animated'],
		));

		$this->last_order = $insert_order;
	}

	public function clear_similar() {
		Database::delete('art_variation', 'art_id = ?', $this->get_id());

		$this->last_order_known = true;
		$this->last_order = false;
	}

	public function calculate_resize() {
		$file = IMAGES.SL.'booru'.SL.'full'.SL.
			$this->get('md5').'.'.$this->get('extension');

		$sizes = getimagesize($file);
		$width = $sizes[0];
		$height = $sizes[1];
		$weight = filesize($file);

		if ($weight > 1024*1024) {
			$weight = round($weight/(1024*1024),1).' мб';
		} elseif ($weight > 1024) {
			$weight = round($weight/1024,1).' кб';
		} else {
			$weight = $weight.' б';
		}

		$this->set('resized', $width.'x'.$height.'px; '.$weight);
	}
}
