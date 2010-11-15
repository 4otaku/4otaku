<?

class transform__video
{
	function html($link, $nico = true) {
		$parts = parse_url($link);
		if (substr($parts['host'],0,4) == 'www.') $parts['host'] = substr($parts['host'],4);

		switch ($parts['host']) {
			case 'youtube.com': $object = $this->youtube($this->parse_query($parts['query'])); break;
			case 'nicovideo.jp': $object = $nico ? $this->nicovideo($parts['path']) : false; break;
			case 'amvnews.ru': $object = $this->amvnews($this->parse_query($parts['query'])); break;
			case 'dailymotion.com': $object = $this->dailymotion($parts['path']); break;
			case 'gametrailers.com': $object = $this->gametrailers($parts['path']); break;
			default: $object = false;
		}
		
		return $object;
	}
	
	function youtube($get) {
		if (strlen($get['v']) == 11)
			return '<object width="%video_width%" height="%video_height%">
					<param name="movie" value="http://www.youtube.com/v/'.$get['v'].'&hl=ru_RU&fs=1&border=0"></param>
					<param name="allowFullScreen" value="true"></param>
					<param name="allowscriptaccess" value="always"></param>
					<embed src="http://www.youtube.com/v/'.$get['v'].'&hl=ru_RU&fs=1&border=0" type="application/x-shockwave-flash" 
					allowscriptaccess="always" allowfullscreen="true" width="%video_width%" height="%video_height%"></embed>
					</object>';
		else return false;
	}

	function nicovideo($path) {
		$parts = explode('/',$path);
		if ($part = array_search('watch',$parts) && $id = $parts[$part+2])
			return '<script type="text/javascript" src="http://ext.nicovideo.jp/thumb_watch/'.$id.'"></script>';
		else return false;
	}
	
	function amvnews($get) {
		if ($get['id'] && is_numeric($get['id']))
			return '<center>
						<object width="%video_width%" height="%video_height%">
						<embed src="http://amvnews.ru/Video/player.swf" width="%video_width%" height="%video_height%" 
						allowscriptaccess="always" allowfullscreen="true" flashvars="height=%video_height%&amp;width=%video_width%&amp;'.
						'file=http%3A%2F%2Famvnews.ru%2Findex.php%3Fgo%3DFiles%26file%3Dse%26id%3D'.$get['id'].'&amp;searchbar=false&amp;'.
						'smoothing=true&amp;backcolor=CCFFFF&amp;frontcolor=000000" /></object>
					</center>';
		else return false;		
	}	
	
	function dailymotion($path) {
		if ($id = substr($path,1,strpos($path,'_') - 1))
			return '<object width="%video_width%" height="%video_height%">
					<param name="movie" value="http://www.dailymotion.com/swf/'.$id.'" />
					<param name="allowFullScreen" value="true" />
					<param name="allowScriptAccess" value="always" />
					<embed src="http://www.dailymotion.com/swf/'.$id.'" type="application/x-shockwave-flash" width="%video_width%" 
					height="%video_height%" allowFullScreen="true" allowScriptAccess="always"></embed>
					</object>';
		else return false;
	}	
		
	function gametrailers($path) {
		$parts = explode('/',$path);
		foreach ($parts as $part) if (is_numeric($part)) $id = $part;
		if ($id)
			return '<div style="width: %video_width%px;">
						<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
						codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" 
						id="gtembed" width="%video_width%" height="%video_height%">	
						<param name="allowScriptAccess" value="sameDomain" /> 
						<param name="allowFullScreen" value="true" /> 
						<param name="movie" value="http://www.gametrailers.com/remote_wrap.php?mid='.$id.'"/>
						<param name="quality" value="high" /> 
						<embed src="http://www.gametrailers.com/remote_wrap.php?mid='.$id.'" swLiveConnect="true" 
						name="gtembed" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" 
						quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" 
						type="application/x-shockwave-flash" width="%video_width%" height="%video_height%"></embed> 
						</object>';
		else return false;
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
?>


