<?php

class Transform_Video
{
	protected $aspect = 0;
	protected $id = '';
	protected $url = array();
	protected $nico = false;
	protected $width = 0;
	protected $height = 0;

	public function __construct($link) {
		$url = parse_url($link);

		if (empty($url)) {
			throw new Error('Incorrect video link');
		}

		if (substr($url['host'], 0, 4) == 'www.') {
			$url['host'] = substr($url['host'], 4);
		}

		$this->url = $url;
	}

	public function disable_nico() {
		$this->nico = false;
		return $this;
	}

	public function enable_nico() {
		$this->nico = true;
		return $this;
	}

	public function set_sizes($sizes, $height = false) {
		if (!empty($height) && is_numeric($sizes)) {
			$sizes = array($sizes, $height);
		}

		if (!is_array($sizes)) {
			$sizes = explode('x', sets::video($sizes));
		}

		$this->width = $sizes[0];
		$this->height = $sizes[1];

		return $this;
	}

	public function get_id() {
		return $this->id;
	}

	public function get_aspect() {
		return $this->aspect;
	}

	public function get_html() {

		$host = $this->url['host'];
		$query = $this->parse_query($this->url['query']);
		$path = $this->url['path'];

		switch ($host) {
			case 'youtube.com':
				$object = $this->youtube($query);
				break;
			case 'vimeo.com':
				$object = $this->vimeo($path);
				break;
			case 'nicovideo.jp':
				if ($this->nico) {
					$object = $this->nicovideo($path);
				} else {
					$object = false;
				}
				break;
			case 'amvnews.ru':
				$object = $this->amvnews($query);
				break;
			case 'dailymotion.com':
				$object = $this->dailymotion($path);
				break;
			case 'gametrailers.com':
				$object = $this->gametrailers($path);
				break;
			case 'rutube.ru':
				$object = $this->rutube($query);
				break;
			default:
				$object = false;
		}

		return $object;
	}

	protected function youtube($get) {
		if (strlen($get['v']) == 11) {
			$this->id = $get['v'];
			$this->aspect = 3/4;

			return '<object width="'.$this->get_width().'" height="'.$this->get_height().'">
				<param name="movie" value="http://www.youtube.com/v/'.$get['v'].'&hl=ru_RU&fs=1&border=0"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="transparent" />
				<embed src="http://www.youtube.com/v/'.$get['v'].'&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash"
				allowscriptaccess="always" allowfullscreen="true" width="'.$this->get_width().'" height="'.$this->get_height().'" wmode="transparent"></embed>
			</object>';
		}
	}

	protected function vimeo($path) {
		$id = array_shift(array_filter(explode('/',$path)));
		if (is_numeric($id)) {
			$this->id = $id;
			$this->aspect = 9/16;

			return '<iframe src="http://player.vimeo.com/video/'.$id.'" width="'.$this->get_width().'" height="'.$this->get_height().'" frameborder="0"></iframe>';
		}
	}

	protected function nicovideo($path) {
		$parts = explode('/',$path);
		if ($part = array_search('watch',$parts) && $id = $parts[$part+2]) {
			$this->id = $id;
			$this->aspect = 1;

			return '<script type="text/javascript" src="http://ext.nicovideo.jp/thumb_watch/'.$id.'"></script>';
		}
	}

	protected function amvnews($get) {
		if ($get['id'] && is_numeric($get['id'])) {
			$this->id = $get['id'];
			$this->aspect = 3/4;

			return '<center>
				<object width="'.$this->get_width().'" height="'.$this->get_height().'">
				<param name="wmode" value="transparent" />
				<embed src="http://amvnews.ru/Video/player.swf" width="'.$this->get_width().'" height="'.$this->get_height().'"
				allowscriptaccess="always" allowfullscreen="true" flashvars="height='.$this->get_height().'&amp;width='.$this->get_width().'&amp;'.
				'file=http%3A%2F%2Famvnews.ru%2Findex.php%3Fgo%3DFiles%26file%3Dse%26id%3D'.$get['id'].'&amp;searchbar=false&amp;'.
				'smoothing=true&amp;backcolor=CCFFFF&amp;frontcolor=000000" wmode="transparent" /></object>
			</center>';
		}
	}

	protected function dailymotion($path) {
		if ($id = substr($path,1,strpos($path,'_') - 1)) {
			$this->id = $id;
			$this->aspect = 3/4;

			return '<object width="'.$this->get_width().'" height="'.$this->get_height().'">
				<param name="movie" value="http://www.dailymotion.com/swf/'.$id.'" />
				<param name="allowFullScreen" value="true" />
				<param name="allowScriptAccess" value="always" />
				<param name="wmode" value="transparent" />
				<embed src="http://www.dailymotion.com/swf/'.$id.'" type="application/x-shockwave-flash" width="'.$this->get_width().'"
				height="'.$this->get_height().'" allowFullScreen="true" allowScriptAccess="always" wmode="transparent"></embed>
			</object>';
		}
	}

	protected function gametrailers($path) {
		$parts = explode('/',$path);
		foreach ($parts as $part) if (is_numeric($part)) $id = $part;
		if (!empty($id)) {
			$this->id = $id;
			$this->aspect = 3/4;

			return '<div style="width: '.$this->get_width().'px;">
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
				codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
				id="gtembed" width="'.$this->get_width().'" height="'.$this->get_height().'">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="allowFullScreen" value="true" />
				<param name="wmode" value="transparent" />
				<param name="movie" value="http://www.gametrailers.com/remote_wrap.php?mid='.$id.'"/>
				<param name="quality" value="high" />
				<embed src="http://www.gametrailers.com/remote_wrap.php?mid='.$id.'" swLiveConnect="true"
				name="gtembed" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true"
				quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer"
				type="application/x-shockwave-flash" width="'.$this->get_width().'" height="'.$this->get_height().'" wmode="transparent"></embed>
			</object>';
		}
	}

	protected function rutube($get) {
		if ($get['v']) {
			$this->id = $get['v'];
			$this->aspect = 3/4;

			return '
				<OBJECT width="'.$this->get_width().'" height="'.$this->get_height().'">
					<PARAM name="movie" value="http://video.rutube.ru/'.$get['v'].'"></PARAM>
					<param name="wmode" value="transparent" />
					<PARAM name="allowFullScreen" value="true"></PARAM>
					<EMBED src="http://video.rutube.ru/'.$get['v'].'"
						type="application/x-shockwave-flash" wmode="window"
						width="'.$this->get_width().'" height="'.$this->get_height().'" allowFullScreen="true"
						wmode="transparent">
					</EMBED>
				</OBJECT>';
		}
	}

	protected function parse_query($query) {
		$query = urldecode($query);
		$parts = explode('&', $query);
		foreach ($parts as $part) {
			$piece = explode('=', $part);
			$return[$piece[0]] = $piece[1];
		}
		return $return;
	}

	protected function get_width() {
		return empty($this->width) ? '%video_width%' : $this->width;
	}

	protected function get_height() {
		return empty($this->height) ? '%video_height%' : $this->height;
	}
}
