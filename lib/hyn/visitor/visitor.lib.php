<?PHP
if(!defined("HYN")) { exit; }

/**
*		start session and visitor information
*		
*/


session_start();

class SiteVisitor {
	

	public function login() {
		
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
						
						// find any redirect back to a previous page
						if( GPC::get_string("return") ) {
							$return		= urldecode( GPC::get_string( "return" ));
							_p_redirect( $return );
						}
						
						return true;
					} else {
						# show error [TODO]	- banned
						$this -> error['login']['general']	= _("Your access to this site has been disallowed.");
					}
				} else {
					# show error [TODO]	- wrong pw
					$this -> error['login']['password']		= _("Failed to log you in; incorrect password.");
				}
			} else {
				# show error [TODO] - user not found	
				$this -> error['login']['username']			= _("Failed to log you in; incorrect username.");
			}
		} else {
			if( !GPC::post_string("login-username") && !GPC::post_string("login-password")) {
				$this -> error['login']['general']				= _("Please enter your username &amp; password.");
			}
			elseif( !GPC::post_string("login-username")) {
				$this -> error['login']['username']				= _("Please enter your username.");
			}
			elseif( !GPC::post_string("login-password")) {
				$this -> error['login']['password']				= _("Please enter your password.");
			}
		}
		return false;
	}
	public function register() {
		if( $email = GPC::post_string("signup-email") ) {
			if( !_v( $email , "email" )) {
				$this -> error['signup']['email']				= _("Please enter a valid e-mail address.");
				return false;
			}
			
			# check for existing
			if( SiteUser::find_one_by_un( $email )) {
				$this -> error['signup']['email']				= _("E-mail address is already registered on this website.");
				return false;
			}
			if( SystemUser::find_one_by_un( $email )) {
				$this -> error['signup']['email']				= _("E-mail address is already known in the system.");
				return false;
			}
			
			if( $password = GPC::post_string("signup-password") ) {
				# validate pass
				if( _v( $password , "password" ) !== TRUE) {
					$this -> error['signup']['password']		= _v( $password , "password" );
					return false;
				}
			} else {
				$this -> error['signup']['password']			= _("Please enter a password.");
				return false;
			}
			if( $password2 = GPC::post_string("signup-password2") ) {
				# check if matches with first pw
				if( $password != $password2 ) {
					$this -> error['signup']['password2']		= _("Passwords do not match, please try again.");
					return false;
				}
			} else {
				$this -> error['signup']['password2']			= _("You need to enter your password twice.");
				return false;
			}
			if( $realname = GPC::post_string("signup-realname") ) {

			} else {
				$this -> error['signup']['realname']			= _("Please enter a real name.");
				return false;
			}
			
			if( !MultiSite::setting( "registration" , "login" ) && !HYN_DEBUG ) {
			
				$this -> error['signup']['general']				= _("Registration is currently unavailable - but will be possible soon.");
				return false;
			
			}
			try {
				$su		= new SiteUser;
				$su -> set( "email" , $email );
				$su -> set( "realname" , $realname );
				$su -> set( "password" , crypt( $password ));
				$su -> set( "domain" , HYN_SYSTEM_ID );
				$su -> set( "signedup" , AnewtDatetime::now() );
				$su -> set( "lastactivity" , AnewtDatetime::now());
				$su -> set( "state" , 1 );								# state 1 for unverified, 9 for verified
				$su -> save( true );
				
				$this -> user	= $su;
				$this -> saveSession();
				
#				$this -> sendRegistrationMail();
			} catch( Exception $e ) {
				$this -> error['signup']['general']				= _("Signing up failed.");
				return false;
			}
			return true;
		}
		else {
			# no email address
			$this -> error['signup']['email']					= _("Please enter your e-mail address.");
			return false;
		}
	}
	private function sendRegistrationMail() {
		global $MultiSite;
		hyn_include( "swiftmailer" );
		# first setup the mail message
		$body		= 	Twig::parse( filefind("mail.register","login") , array( "user" => $this -> user ) );
		
		$message 	= Swift_Message::newInstance();
		$message -> setSubject( _("Registration on ") . $MultiSite -> get("domain") );
		$message -> setBody( $body , "text/html" );
		$message -> setTo( $this -> user -> email );
		$message -> setFrom( "no-reply@" . $MultiSite -> get("domain") );
		sendEmail( $message );
	}
	public function __construct() {
		
		anewt_include( "gpc" );
		
		$this		-> ip 				= $_SERVER['REMOTE_ADDR'] ;
		$this		-> host				= gethostbyaddr( $_SERVER['REMOTE_ADDR'] );	
		$this 		-> sessionid		= session_id();
		// 
		if( isset($_SESSION['user']) ) {
			$this -> readSession();
			if( GPC::post_string("logout-user") || GPC::get_string("logout-user")) {
				session_unset();
				unset($this -> user);
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
		if( !$this -> loggedin() ) {
			return false;
		}
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
	
	/*
	*	temporary fix? [todo]
	*/
	static $user_cache								= array();
	
	public static function get_by_un( $un , $onlysite=false ) {
		if( !$onlysite ) {
			if( isset( self::$user_cache[0][$un] )) {
				return self::$user_cache[0][$un];
			}
			elseif( !is_null( $s = SystemUser::find_one_by_un( $un )) ) {
				self::$user_cache[0][$un]			= $s;
				return $s;
			}
		}
		if( isset( self::$user_cache[HYN_SYSTEM_ID][$un] )) {
			return self::$user_cache[HYN_SYSTEM_ID][$un];
		}
		elseif( !is_null( $u = SiteUser::find_one_by_un( $un ))) {
			self::$user_cache[HYN_SYSTEM_ID][$un]	= $u;
			return $u;
		}
		return false;
	}
}