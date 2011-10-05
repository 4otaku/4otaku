<?

class Transform_Video extends transform__video {}

class transform__video
{
	public $aspect = 0;
	public $id = '';

	function html($link, $nico = true) {
		$parts = parse_url($link);
		if (substr($parts['host'],0,4) == 'www.') $parts['host'] = substr($parts['host'],4);
		$this->id = ''; $this->aspect = 0; $this->api = false;

		switch ($parts['host']) {
			case 'youtube.com': $object = $this->youtube($this->parse_query($parts['query'])); break;
			case 'vimeo.com': $object = $this->vimeo($parts['path']); break;
			case 'nicovideo.jp': $object = $nico ? $this->nicovideo($parts['path']) : false; break;
			case 'amvnews.ru': $object = $this->amvnews($this->parse_query($parts['query'])); break;
			case 'dailymotion.com': $object = $this->dailymotion($parts['path']); break;
			case 'gametrailers.com': $object = $this->gametrailers($parts['path']); break;
			case 'rutube.ru': $object = $this->rutube($this->parse_query($parts['query'])); break;
			default: $object = false;
		}

		if (!empty($object)) {
			return $object;
		} else {
			return false;
		}
	}

	function youtube($get) {
		if (strlen($get['v']) == 11) {
			$this->id = $get['v'];
			$this->aspect = 3/4;

			return '<object width="%video_width%" height="%video_height%">
					<param name="movie" value="http://www.youtube.com/v/'.$get['v'].'&hl=ru_RU&fs=1&border=0"></param>
					<param name="allowFullScreen" value="true"></param>
					<param name="allowscriptaccess" value="always"></param>
					<param name="wmode" value="transparent" />
					<embed src="http://www.youtube.com/v/'.$get['v'].'&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash"
					allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%" wmode="transparent"></embed>
					</object>';
		}
	}

	function vimeo($path) {
		$id = array_shift(array_filter(explode('/',$path)));
		if (is_numeric($id)) {
			$this->id = $id;
			$this->aspect = 9/16;

			return '<iframe src="http://player.vimeo.com/video/'.$id.'" width="%video_width%" height="%video_height%" frameborder="0"></iframe>';
		}
	}

	function nicovideo($path) {
		$parts = explode('/',$path);
		if ($part = array_search('watch',$parts) && $id = $parts[$part+2]) {
			$this->id = $id;
			$this->aspect = 1;

			return '<script type="text/javascript" src="http://ext.nicovideo.jp/thumb_watch/'.$id.'"></script>';
		}
	}

	function amvnews($get) {
		if ($get['id'] && is_numeric($get['id'])) {
			$this->id = $get['id'];
			$this->aspect = 3/4;

			return '<center>
						<object width="%video_width%" height="%video_height%">
						<param name="wmode" value="transparent" />
						<embed src="http://amvnews.ru/Video/player.swf" width="%video_width%" height="%video_height%"
						allowscriptaccess="always" allowfullscreen="true" flashvars="height=%video_height%&amp;width=%video_width%&amp;'.
						'file=http%3A%2F%2Famvnews.ru%2Findex.php%3Fgo%3DFiles%26file%3Dse%26id%3D'.$get['id'].'&amp;searchbar=false&amp;'.
						'smoothing=true&amp;backcolor=CCFFFF&amp;frontcolor=000000" wmode="transparent" /></object>
					</center>';
		}
	}

	function dailymotion($path) {
		if ($id = substr($path,1,strpos($path,'_') - 1)) {
			$this->id = $id;
			$this->aspect = 3/4;

			return '<object width="%video_width%" height="%video_height%">
					<param name="movie" value="http://www.dailymotion.com/swf/'.$id.'" />
					<param name="allowFullScreen" value="true" />
					<param name="allowScriptAccess" value="always" />
					<param name="wmode" value="transparent" />
					<embed src="http://www.dailymotion.com/swf/'.$id.'" type="application/x-shockwave-flash" width="%video_width%"
					height="%video_height%" allowFullScreen="true" allowScriptAccess="always" wmode="transparent"></embed>
					</object>';
		}
	}

	function gametrailers($path) {
		$parts = explode('/',$path);
		foreach ($parts as $part) if (is_numeric($part)) $id = $part;
		if (!empty($id)) {
			$this->id = $id;
			$this->aspect = 3/4;

			return '<div style="width: %video_width%px;">
						<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
						codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
						id="gtembed" width="%video_width%" height="%video_height%">
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="allowFullScreen" value="true" />
						<param name="wmode" value="transparent" />
						<param name="movie" value="http://www.gametrailers.com/remote_wrap.php?mid='.$id.'"/>
						<param name="quality" value="high" />
						<embed src="http://www.gametrailers.com/remote_wrap.php?mid='.$id.'" swLiveConnect="true"
						name="gtembed" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true"
						quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer"
						type="application/x-shockwave-flash" width="%video_width%" height="%video_height%" wmode="transparent"></embed>
						</object>';
		}
	}

	function rutube($get) {
		if ($get['v']) {
			$this->id = $get['v'];
			$this->aspect = 3/4;

			return '
				<OBJECT width="%video_width%" height="%video_height%">
					<PARAM name="movie" value="http://video.rutube.ru/'.$get['v'].'"></PARAM>
					<param name="wmode" value="transparent" />
					<PARAM name="allowFullScreen" value="true"></PARAM>
					<EMBED src="http://video.rutube.ru/'.$get['v'].'"
						type="application/x-shockwave-flash" wmode="window"
						width="%video_width%" height="%video_height%" allowFullScreen="true"
						wmode="transparent">
					</EMBED>
				</OBJECT>';
		}
	}

	function parse_query($query) {
		$query = urldecode($query);
		$parts = explode('&',$query);
		foreach ($parts as $part) {
			$piece = explode('=',$part);
			$return[$piece[0]] = $piece[1];
		}
		return $return;
	}
}
