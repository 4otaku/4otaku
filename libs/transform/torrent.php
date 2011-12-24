<?php

class Transform_Torrent {

	// данные для декодирования
	protected $pos = 0;
	protected $src = '';
	protected $src_len = 0;

	protected $data = array();
	protected $decoded = false;

	function __construct($file = false) {

		if (!empty($file)) {
			$data = file_get_contents($file);

			$this->load($data);
		}
	}

	public function encode() {
		if (!$this->decoded) {
			$this->decode();
		}

		return $this->encode_internal($this->data);
	}

	public function get_hash() {
		if (!$this->decoded) {
			$this->decode();
		}

		if (empty($this->data['info'])) {
			return false;
		}

		$encoded = $this->encode_internal($this->data['info']);
		return sha1($encoded);
	}

	public function get_size() {
		$size = 0;

		$info = $this->get('info');

		if (!empty($info['length'])) {
			$size += (int) $info['length'];
		}

		if (!empty($info['files'])) {
			foreach ($info['files'] as $file) {
				$size += (int) $file['length'];
			}
		}

		return $size;
	}

	public function encode_internal($v) {
		switch (gettype($v)) {
			case 'integer':
			case 'double':
				return $this->encode_int((float)$v);
			case 'boolean':
				return $this->encode_bool($v);
			case 'string':
				return $this->encode_string($v);
			case 'array':
				return $this->encode_list($v);
			default:
				return $this->encode_string('');
		}
	}

	public function encode_int($i) {
		return 'i'.sprintf('%.0f', $i).'e';
	}

	public function encode_string($s) {
		$s = (string)$s;
		$s = sprintf('%d:%s', strlen($s), $s);
		return $s;
	}

	public function encode_bool($b) {
		return $this->encode_int($b ? 1 : 0);
	}

	public function encode_list($l) {
		$l = (array)$l;
		$is_list = true;
		$keys = array_keys($l);
		foreach ($keys as $key) {
			if (!is_int($key)) {
				$is_list = false;
				break;
			}
		}

		if ($is_list) {
			ksort($l, SORT_NUMERIC);
			$res = array('l');
			foreach ($l as $v) {
				$res[] = self::encode_internal($v);
			}
		}
		else {
			//ksort($l, SORT_STRING);
			$res = array('d');
			foreach ($l as $k => $v) {
				$res[] = self::encode_internal((string)$k);
				$res[] = self::encode_internal($v);
			}
		}

		$res = implode('', $res).'e';
		return $res;
	}

	public function load($data) {
		$this->pos = 0;
		$this->src = (string) $data;
		$this->src_len = strlen($this->src);
		$this->decoded = false;

		return $this;
	}

	public function is_valid() {
		if (!$this->decoded) {
			$this->decode();
		}

		return is_array($this->data);
	}

	public function decode($ignore_tail = false) {

		$res = $this->decode_internal();
		// условие сработает, если в конце последовательности мусорные символы
		if (!$ignore_tail && ($this->pos != $this->src_len)) {
			$this->data = false;
		} else {
			$this->data = $res;
		}

		$this->decoded = true;
	}

	public function delete($name) {
		if ($this->is_valid() && isset($this->data[$name])) {
			unset($this->data[$name]);
		}

		return $this;
	}

	public function set($name, $value) {
		if ($this->is_valid()) {
			$this->data[$name] = $value;
		}

		return $this;
	}

	public function get($name = false, $section = false) {
		if (!$this->is_valid()) {
			return false;
		}

		$return = $this->data;

		if (!empty($name)) {
			if (!isset($return[$name])) {
				return false;
			}

			$return = $return[$name];
		}

		if (!empty($section)) {
			if (!isset($return[$section])) {
				return false;
			}

			$return = $return[$section];
		}

		return $return;
	}

	private function next_char() {
		if ($this->pos >= $this->src_len)
			return false;
		else
			return (string)$this->src[ $this->pos++ ];
	}

	private function rev_char() {
		if ($this->pos > 0) --$this->pos;
	}

	/**
	 * Чтение следующего элемента из потока
	 *
	 * @return mixed                    Элемент bencode
	 */
	protected function decode_internal() {
		$char = $this->next_char();
		switch ($char) {
			case 'i':
				return $this->decode_int();
			case 'l':
				return $this->decode_list();
			case 'd':
				return $this->decode_dict();
			default:
				$this->rev_char();
				return $this->decode_string();
		}
	}

	/**
	 * Чтение числа из потока
	 *
	 * Целое число записывается так: i<число в десятичной системе счисления>e.
	 * Число не должно начинаться с нуля, но число ноль записывается как "i0e".
	 * Отрицательные числа записываются со знаком минуса перед числом.
	 * Число 42 будет выглядеть так "i42e".
	 *
	 * @param string $suffix            Окончание числа
	 * @return string                   Полученное число строкой либо false
	 */
	protected function decode_int($suffix = 'e') {
		$pos = &$this->pos;
		$src = &$this->src;
		$e_pos = strpos($src, $suffix, $pos);
		if (($e_pos === false) || ($e_pos == $pos)) return false;

		$neg = ($src[ $pos ] == '-');
		if ($neg) ++$pos;

		if ($src[ $pos ] == '0') {
			if ($e_pos > $pos+1) return false;
		}

		for ($i = $pos; $i < $e_pos; $i++) {
			if (!ctype_digit($src[$i])) return false;
		}

		$res = substr($src, $pos, $e_pos-$pos);
		$pos = $e_pos+1;
		$return = $neg ? '-'.$res : $res;
		return (int) $return;
	}

	/**
	 * Чтение строки из потока
	 *
	 * Строка байт: <размер>:<содержимое>.
	 * Размер — это число в десятичной системе счисления.
	 * Содержимое — это непосредственно данные, представленные цепочкой байт.
	 * Строка "spam" в этом формате будет выглядеть так "4:spam".
	 *
	 * @return string                   Полученная строка или false
	 */
	protected function decode_string() {
		$pos = &$this->pos;
		$src = &$this->src;

		// длина описывается натуральным числом?
		$len = $this->decode_int(':');
		if (($len === false) || ($len < 0)) return false;

		// конец строки за пределами буфера?
		if ($pos + $len >= $this->src_len) return false;

		$p = $pos;
		$pos += $len;
		return substr($src, $p, $len);
	}

	/**
	 * Чтение списка (массива) из потока
	 *
	 * Список (массив): l<содержимое>e.
	 * Содержимое включает в себя любые Bencode типы, следующие друг за другом.
	 * Список, состоящий из строки «spam» и числа 42, будет выглядеть так:
	 *   "l4:spami42ee".
	 *
	 * @return array                    Итоговый список или false
	 */
	protected function decode_list() {
		$pos = &$this->pos;
		$src = &$this->src;
		$src_len = &$this->src_len;

		$res = array();

		while (1) {
			if ($pos >= $src_len) return false;

			if ($src[ $pos ] == 'e') {
				++$pos;
				break;
			}
			else {
				$_ = $this->decode_internal();
				if ($_ === false) return false;
				$res[] = $_;
			}
		}

		return $res;
	}

	/**
	 * Чтение словаря (хеша) из потока
	 *
	 * Словарь: d<содержимое>e.
	 * Содержимое состоит из пар Ключ-Значение, которые следуют друг за другом.
	 * Ключ может быть только строкой байт.
	 * Значение может быть любым Bencode элементом.
	 * Если сопоставить ключам «bar» и «foo» значения «spam» и 42,
	 *   получится: «d3:bar4:spam3:fooi42ee».
	 * Если добавить пробелы между элементами, будет легче понять структуру:
	 *   "d 3:bar 4:spam 3:foo i42e e".
	 *
	 * @return array                    Хеш или false
	 */
	protected function decode_dict() {
		$pos = &$this->pos;
		$src = &$this->src;
		$src_len = &$this->src_len;

		$res = array();

		while (1) {
			if ($pos >= $src_len) return false;

			if ($src[ $pos ] == 'e') {
				++$pos;
				break;
			}
			else {
				$key = $this->decode_string();
				if ($key === false) return false;

				$value = $this->decode_internal();
				if ($value === false) return false;

				$res[ $key ] = $value;
			}
		}

		return $res;
	}

}
