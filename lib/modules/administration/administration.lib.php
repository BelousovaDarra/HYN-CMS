<?PHP
if( !defined("HYN")) { exit; }


class administration extends module {
	public function _administration() {
		switch( $this -> route -> path[1] ) {
			case "invoice":
				$this -> invoice();
				break;
			case "relation":
				$this -> relation();
				break;
			case "product":
				$this -> product();
				break;
			default:
				$this -> overview();
		}
	}
	public function display() {
		
	}
} 
