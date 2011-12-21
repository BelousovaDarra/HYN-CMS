<?PHP
if(!defined("HYN")) { exit; }

class visitor extends Container {
	static public $v;
	
	static function init() {
		$v			= new visitor;
		$v			-> set( "ip" 	, $_SERVER['REMOTE_ADDR'] );
		$v			-> set( "host"	, gethostbyaddr( $_SERVER['REMOTE_ADDR'] ));
		self::$v					= $v;
	}
	static function login() {
		anewt_include( "gpc" );
		if( $un = GPC::post_string("login-username") && $pw = GPC::post_string("login-password") ) {
			$remember				= GPC::post_bool("login-remember");
		} else {
			return false;
		}
	}
}