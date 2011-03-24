<?

class Controller implements Plugins
{
	// Хранит рабочий контроллер
	private $worker;

	function __construct() {
		if (!empty(Globals::$user_data['mobile'])) {
			$this->worker = new Controller_Mobile();
			return;
		}

		if (!empty(Globals::$vars['ajax'])) {
			$this->worker = new Controller_Ajax();
			return;
		}

		$this->worker = new Controller_Web();
	}

	function build() {
		if (!($this->worker instanceOf Controller_Abstract)) {
			$name = get_class($this->worker);
			Error::fatal("Контроллер $name не является ребенком Controller_Abstract");
		}

		return $this->worker->build();
	}

    public function __toString() {
		return preg_replace('/^[a-z]+?_/i', '', get_class($this->worker));
    }
}
