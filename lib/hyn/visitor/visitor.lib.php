<?PHP
if(!defined("HYN")) { exit; }

/**
*		start session and visitor information
*		
*/

session_start();

class SiteVisitor {
	
	
	
	private function login() {
		anewt_include( "gpc" );
		if( ($un = GPC::post_string("login-username")) && ($pw = GPC::post_string("login-password") )) {
			$remember				= GPC::post_bool("login-remember");
			# check for System & Site USER
			if( !is_null($s = SystemUser::find_one_by_un( $un )) || !is_null($u = SiteUser::find_one_by_un( $un )) ) {
				# prioritize system user above site user
				if( $s ) { $user = $s; } elseif( $u ) { $user = $u; }
				# user found, check pw
				if( crypt( $pw , $user -> get("password")) ) {
					# password is correct, check for state
					if( !$user -> get("banned") ) {
						$this	-> user		= $user;
						$this -> saveSession();
						return true;
					} else {
						# show error [TODO]	- banned
						_debug( "banned" );
					}
				} else {
					# show error [TODO]	- wrong pw
					_debug( "wrong password" );
				}
			} else {
				# show error [TODO] - user not found	
				_debug( "user not found in database" );
			}
		}
		return false;
	}
	public function __construct() {
		$this		-> ip 				= $_SERVER['REMOTE_ADDR'] ;
		$this		-> host				= gethostbyaddr( $_SERVER['REMOTE_ADDR'] );	
		
		if( isset($_SESSION['user']) ) {
			$this -> readSession();
			if( GPC::post_string("logout-user") || GPC::get_string("logout-user")) {
				session_unset();
				unset($this -> user);
			}
		}
		
		elseif( GPC::post_string("login-user") ) {
			if( $this -> login() ) {
				# show public notification welcome	
			}
		}
	}
	private function readSession() {
		$this -> user				= unserialize($_SESSION['user']);
	}
	private function saveSession() {
		$_SESSION['user']				= serialize($this -> user);
	}
	public function loggedin() {
		if( isset($this -> user)) {
			return true;
		} else {
			return false;
		}
	}
	public function get_right( $r="owner" ) {
		global $MultiSite;
		$owner		= $MultiSite -> get( "owner" );
		// if system admin
		if( $this -> user -> get("type") == 0 && $this -> user -> get("admin")) {
			return true;
		} else
		// if site owner
		if( $owner == $this -> user -> get( "uid" )) {
			return true;
		}
		// if site rights
#		elseif( SystemUserRight::find_one_by_uid_site( $this -> user -> get("uid") , $MultiSite -> get("id") ) ) {
			
#		}
		else {
			return false;
		}
	}
}