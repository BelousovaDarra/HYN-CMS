<?PHP

if( !defined("HYN")) { exit; }

class restclient {
	static $client	= NULL;
	private function __construct() {
	
	}
	public static function get_instance() {
		if( is_null(self::$client) ) {
			self::$client	= new restclient();
		}
		return self::$client;
	}
	public static function setup( $un=false , $pw=false ) {
		
	}
}
