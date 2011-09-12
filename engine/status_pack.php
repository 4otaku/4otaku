<?

	include '../inc.common.php';

	$params = array('pack_status', query::$get['id']);
	$status = Database::get_full_row('misc', 'type = ? and data2 = ?', $params);

	switch ($status['data1']) {
		case 'starting':
			echo "Ожидается очередь на распаковку.";
			break;
		case 'error':
			echo "Злые хорьки сгрызли ваш архив.";
			break;
		case 'unpacking':
			echo "Распаковывается.";
			break;
		case 'unpacked':
			echo "Поиск картинок в распакованном.";
			break;
		case 'processing':
			echo "Обрабатываюся картинки. $status[data3]/$status[data4]";
			break;
		case 'done':
			echo "Готово.";
			break;
		default:
			echo "Произошла неведомая фигня.";
			break;
	}
