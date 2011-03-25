<?

class Process_Index implements Process_Interface
{
	public function process_web ($data) {
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
