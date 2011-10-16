<?
abstract class Dynamic_Abstract
{
	protected function reply ($data, $success = true) {
		$response = array(
			'success' => $success,
			'data' => $data,
		);

		$response = htmlspecialchars(json_encode($response), ENT_NOQUOTES);
		exit($response);
	}
}
