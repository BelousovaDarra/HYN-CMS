<?PHP
if( !defined("HYN")) { exit; }

class login extends module {
	
	protected function SSL() {
		return TRUE;
	}
	public function _login() {
		DOM::set_title( "Sign up & log in" );
	}
	public function display() {
		return $this -> parseTemplate( "login" );
	}
}