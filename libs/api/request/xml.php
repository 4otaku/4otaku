<?php

class Api_Request_Xml extends Api_Request_Abstract
{
	public function convert($input) {
		if (!function_exists('xml_parser_create')) {
			throw new Error_Api_Request('no xml parser');
		}

		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($input), $xml_values);
		xml_parser_free($parser);

		if (!$xml_values) {
			throw new Error_Api_Request('incorrect xml or encoding');
		}

		$xml_array = array();
		$parents = array();
		$opened_tags = array();
		$arr = array();
		$current = & $xml_array;
		$repeated_tag_index = array();

		foreach ($xml_values as $data) {
			unset ($attributes, $value);
			extract($data);
			$result = array();
			$attributes_data = array();
			if (isset ($value)) {
				if ($priority == 'tag') {
					$result = $value;
				} else {
					$result['value'] = $value;
				}
			}
			if (isset ($attributes) && $get_attributes) {
				foreach ($attributes as $attr => $val) {
					if ($priority == 'tag') {
						$attributes_data[$attr] = $val;
					} else {
						$result['attr'][$attr] = $val;
					}
				}
			}
			if ($type == 'open') {
				$parent[$level - 1] = & $current;
				if (!is_array($current) || (!in_array($tag, array_keys($current)))) {
					$current[$tag] = $result;
					if ($attributes_data) {
						$current[$tag . '_attr'] = $attributes_data;
					}
					$repeated_tag_index[$tag . '_' . $level] = 1;
					$current = & $current[$tag];
				} else {
					if (isset ($current[$tag][0])) {
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						$repeated_tag_index[$tag . '_' . $level]++;
					} else {
						$current[$tag] = array (
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 2;
						if (isset ($current[$tag . '_attr'])) {
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset ($current[$tag . '_attr']);
						}
					}
					$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
					$current = & $current[$tag][$last_item_index];
				}
			} elseif ($type == 'complete') {
				if (!isset ($current[$tag])) {
					$current[$tag] = $result;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if ($priority == 'tag' && $attributes_data) {
						$current[$tag . '_attr'] = $attributes_data;
					}
				} else {
					if (isset ($current[$tag][0]) && is_array($current[$tag])) {
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						if ($priority == 'tag' && $get_attributes && $attributes_data) {
							$key = $repeated_tag_index[$tag . '_' . $level] . '_attr';
							$current[$tag][$k] = $attributes_data;
						}
						$repeated_tag_index[$tag . '_' . $level]++;
					} else {
						$current[$tag] = array (
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 1;
						if ($priority == 'tag' && $get_attributes) {
							if (isset ($current[$tag . '_attr'])) {
								$current[$tag]['0_attr'] = $current[$tag . '_attr'];
								unset ($current[$tag . '_attr']);
							} if ($attributes_data) {
								$key = $repeated_tag_index[$tag . '_' . $level] . '_attr';
								$current[$tag][$key] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag . '_' . $level]++;
					}
				}
			} elseif ($type == 'close') {
				$current = & $parent[$level -1];
			}
		}
		return ($xml_array);
	}
}
