<?PHP

if( !defined("HYN")) { exit; }

class ajax extends module {
	public function _ajax() {
		$path		= $this -> route -> path;
		if( count( $path ) < 2 ) { return false; }
		array_shift( $path );
		$c			= array_shift( $path );
		if( !class_exists( $c )) { return false; }
		
		$this -> path	= $path;
		$this -> c	= new $c;
				
	}
	public function display() {
		if( method_exists( $this -> c , "ajax" )) {
			return $this -> c -> ajax();
		}
	}
}