<?PHP

if(!defined("HYN")) { exit; }

require_once "lib" . DS . "webapp" . DS . "init.php";


class thinkup extends module {
	protected function _thinkup() {
	
	}
	public function display() {
		$controller = new DashboardController();
		echo $controller->go();	
	}
}