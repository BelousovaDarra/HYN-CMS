<?PHP
if( !defined("HYN")) { exit; }

class page extends module {
	public function _page() {
		_debug($this -> route -> path);
	}
	public function display() {
		
	}
}