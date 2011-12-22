<?PHP
if(!defined("HYN")) { exit; }

class SiteVisitor {
	static private $v 	= null;
	
	static function get_instance() {
		if( is_null( self::$v )) {
			self::$v	= new SiteVisitor;
		}
		return self::$v;
	}
	
	static function init() {
		$v			= self::get_instance();
	}
	static function login() {
		anewt_include( "gpc" );
		if( $un = GPC::post_string("login-username") && $pw = GPC::post_string("login-password") ) {
			$remember				= GPC::post_bool("login-remember");
		} else {
			return false;
		}
	}
	private function __construct() {
		$this		-> ip 				= $_SERVER['REMOTE_ADDR'] ;
		$this		-> host				= gethostbyaddr( $_SERVER['REMOTE_ADDR'] );	
	}
}