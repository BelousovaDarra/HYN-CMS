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
#		$v			= self::get_instance();
	}
	static function login() {
		anewt_include( "gpc" );
		if( $un = GPC::post_string("login-username") && $pw = GPC::post_string("login-password") ) {
			$remember				= GPC::post_bool("login-remember");
			# check for Portal / Site USER
			if( $u = SiteUser::find_one_by_un( $un ) ) {
				# user found, check pw
				if( crypt( $pw , $u -> get("password")) ) {
					# password is correct, check for state
					if( !$u -> get("banned") ) {
						$v	= self::get_instance();
						$v	-> user		= $u;
						return true;
					} else {
						# show error [TODO]	- banned
					}
				} else {
					# show error [TODO]	- wrong pw
				}
			} else {
				# show error [TODO] - user not found	
			}
		}
		return false;
	}
	private function __construct() {
		$this		-> ip 				= $_SERVER['REMOTE_ADDR'] ;
		$this		-> host				= gethostbyaddr( $_SERVER['REMOTE_ADDR'] );	
		if( GPC::post_string("login-user") ) {
			self::login();
		}
	}
}