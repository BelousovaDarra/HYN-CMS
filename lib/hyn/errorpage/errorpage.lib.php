<?PHP

if( !defined("HYN")) { exit; }

class ErrorPage extends module {
	function _ErrorPage() {
		
	}
	function display() {
		$vars					= array();

		return $this -> parseTemplate( "error-page" , $vars );
	}
}