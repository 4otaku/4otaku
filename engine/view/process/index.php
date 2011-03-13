<?

class Process_Index extends Process_Abstract
{
	public function web ($data) {
		foreach ($data as & $part) {
			if (!empty($part['latest']) && is_array($part['latest'])) {
				
				foreach ($part['latest'] as & $item) {
					if (is_array($item) && !empty($item['text'])) {
						$item['headline'] = Objects::transform('text')->headline($item['text']);
						unset($item['text']);
					}
				}
			}
		}
		
		return $data;
	}
}
