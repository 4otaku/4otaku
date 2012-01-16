<?php

class Api_Response_Xml extends Api_Response_Abstract {

	protected $headers = array('Content-type' => 'application/xml');

	public function encode(Array $data) {

		$xml = new XmlWriter();
		$xml->openMemory();
		$xml->startDocument('1.0', 'UTF-8');
		$xml->startElement('api_answer');

		$this->write($xml, $data);

		$xml->endElement();
		return $xml->outputMemory(true);
	}

	protected function write(XMLWriter $xml, $data) {

		foreach($data as $key => $value){

			if (is_array($value)) {

				if (is_numeric($key)) {
					$xml->startElement('item');
				} else {
					$xml->startElement($key);
				}
				$this->write($xml, $value);
				$xml->endElement();
				continue;
			}

			if (is_bool($value) === true) {
				$value = $value ? 'true' : 'false';
			}

			$xml->writeElement($key, $value);
		}
	}
}
