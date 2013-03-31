<?

class mail
{
	private $errstr;
	private $headers;
	private $textbody;
	private $htmlbody;
	private $attachments;
	private $boundary;
	private $textfunction;
	private $recepient;

	function __construct($recepient) {
		$this->attachments = array();
		$this->boundary = '_mail_'.md5(microtime(true).'4otaku').'_boundary_';
		$this->headers = array(
			 'From' => '4otaku.org <gouf@4otaku.org>',
			 'MIME-Version' => '1.0',
			 'Content-Type' => 'multipart/mixed; boundary="'.$this->boundary.'"',
		);
		$this->recepient = $recepient;
	}

	function get_body() {
		$retval = $this->textbody;
		$retval .= $this->htmlbody;
		foreach($this->attachments as $tblck)
			$retval .= $tblck;

		return $retval;
	}

	function get_header() {
		$retval = "";
		foreach($this->headers as $key => $value)
			$retval .= "$key: $value\n";

		return $retval;
	}

	function set_header($name, $value) {
		$this->headers[$name] = $value;
		return $this;
	}

	function attachfile($file, $type = "application/octetstream") {
		if(!($fd = fopen($file, "r"))) {
			$this->errstr = "Error opening $file for reading.";
			return 0;
		}
		$_buf = fread($fd, filesize($file));
		fclose($fd);

		$fname = pathinfo($file,PATHINFO_BASENAME);
		$_buf = chunk_split(base64_encode($_buf));

		$this->attachments[$file] = "\n--" . $this->boundary . "\n";
		$this->attachments[$file] .= "Content-Type: $type; name=\"$fname\"\n";
		$this->attachments[$file] .= "Content-Transfer-Encoding: base64\n";
		$this->attachments[$file] .= "Content-Disposition: attachment; filename=\"$fname\"\n\n";
		$this->attachments[$file] .= $_buf;

		return $this;
	}

	function text($text) {
		if (preg_match('/@mail\.ru/', $this->recepient)) {
			$this->bodytext(strip_tags($text));
		} else {
			$this->htmltext($text);
		}

		return $this;
	}

	function bodytext($text) {
		$this->textbody = "\n--" . $this->boundary . "\n";
		$this->textbody .= "Content-Type: text/plain charset=utf-8\n";
		$this->textbody .= "Content-Transfer-Encoding: 8bit\n\n";
		$this->textbody .= $text;
	}

	function htmltext($text) {
		$this->htmlbody = "\n--" . $this->boundary . "\n";
		$this->htmlbody .= "Content-Type: text/html; charset=utf-8\n\n";
		$this->htmlbody .= $text;
	}

	function clear_text() { $this->textbody = ""; $this->htmlbody = ""; }

	function clear_bodytext() { $this->textbody = ""; }
	function clear_htmltext() { $this->htmlbody = ""; }
	function get_error() { return $this->errstr; }

	function send($subject = "Уведомление от сайта 4otaku.org") {
		$_body = '';
		if(isset($this->textbody)) $_body .= $this->textbody;
		if(isset($this->htmlbody)) $_body .= $this->htmlbody;

		foreach($this->attachments as $tblck)
			$_body .= $tblck;

		$_body .= "\n--$this->boundary--";

		mail($this->recepient, $subject, $_body, $this->get_header());
	}
}
