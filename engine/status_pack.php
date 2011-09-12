<?

	include '../inc.common.php';
	
	$status = obj::db()->sql('select * from misc where type="pack_status" and data2="'.query::$get['id'].'"',1);

	switch ($status['data1']) {
		case 'starting': echo "Ожидается очередь на распаковку."; break;
		case 'error': echo "Злые хорьки сгрызли ваш архив."; break;
		case 'unpacking': echo "Распаковывается."; break;
		case 'unpacked': echo "Поиск картинок в распакованном."; break;
		case 'processing': echo "Обрабатываюся картинки. ".$status['data3']."/".$status['data4']; break;
		case 'done': echo "Готово."; break;
		default: break;
	}
