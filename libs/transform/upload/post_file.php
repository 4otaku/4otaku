<?php

class Transform_Upload_Post_File extends Transform_Upload_Abstract_Image
{
	protected function get_max_size() {
		return def::post('filesize');
	}

	protected function test_file() {
		$maxsize = $this->get_max_size();

		if ($this->size > $maxsize) {
			throw new Error_Upload(Error_Upload::FILE_TOO_LARGE);
		}
	}

	protected function process() {
		$time = str_replace('.', '', microtime(true));
		$extension =  pathinfo($this->name, PATHINFO_EXTENSION);
		if ($extension != 'mp3') {
			$filename = substr(Transform_File::make_name(
				pathinfo($this->name, PATHINFO_FILENAME)),0,200);
		} else {
			$filename = 'audiotrack';
		}

		$sizefile = Transform_File::weight_short($this->size);

		mkdir(FILES.SL.'post'.SL.$time, 0755);
		$newfile = FILES.SL.'post'.SL.$time.SL.$filename.'.'.$extension;
		chmod($this->file, 0755);
		if (!move_uploaded_file($this->file, $newfile)) {
			file_put_contents($newfile, file_get_contents($this->file));
		}

		$return_data = '';

		if (is_array($this->info)) {
			$newthumb = FILES.SL.'post'.SL.$time.SL.'thumb_'.$filename.'.'.$extension;
			$this->worker = Transform_Image::get_worker($newfile);
			$this->scale(200, $newthumb);
			$type = 'image';
			$name = 'Сэмпл ('.$extension.')';
			$return_data .=
				'<input type="hidden" name="file[0][height]" value="'.
				$this->worker->get_image_height().'" />'."\n";
				
			$this->set_result('height', $this->worker->get_image_height());
		} elseif ($extension == 'mp3') {
			$type = 'audio';

			include_once(ENGINE.SL.'external'.SL.'getid3'.SL.'getid3.php');

			$getid3 = new getID3();
			$getid3->encoding = 'UTF-8';
			$getid3->Analyze($newfile);
			if (
				empty($getid3->info['tags']) ||
				!is_array($getid3->info['tags'])) {

				$name = 'Сэмпл';
			} else {
				$tags = current($getid3->info['tags']);

				if (is_array($tags['artist'])) {
					$tags['artist'] = reset($tags['artist']);
				}

				if (is_array($tags['title'])) {
					$tags['title'] = reset($tags['title']);
				}

				if (empty($tags['artist'])) {
					$tags['artist'] = 'Неизвестный исполнитель';
				}

				if (empty($tags['title'])) {
					$tags['title'] = 'неизвестная композиция';
				}

				$name = $tags['artist'] . ' - '. $tags['title'];
			}
		} else {
			$type = 'plain';
			$name = 'Новый файл';
		}

		$return_data .=
			'<input size="24%" type="text" name="file[0][name]" value="'.$name.'" />:'."\n".
			'<input readonly size="24%" type="text" name="file[0][filename]" value="'.$filename.'.'.$extension.'" />'."\n".
			'<input type="hidden" name="file[0][folder]" value="'.$time.'" />'."\n".
			'<input type="hidden" name="file[0][type]" value="'.$type.'" />'."\n".
			'<input readonly size="4%" type="text" name="file[0][size]" value="'.$sizefile.'" />'."\n".
			'<input type="submit" class="disabled sign remove_link" rel="file" value="-" />';

		$this->set(array(
			'success' => true,
			'data' => $return_data,
			'filename' => $filename.'.'.$extension,
			'type' => $type,
			'folder' => $time,
			'name' => $name,
			'size' => $sizefile,
		));
	}
}
