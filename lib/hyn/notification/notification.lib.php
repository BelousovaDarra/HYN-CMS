<?PHP
if( !defined('HYN') ) { exit; }

class notification {
	static $inst		= NULL;
	static $message		= array();
	static $error		= array();
	static $debug		= array();
	
	private function __construct() {
		error_reporting( E_ALL | E_STRICT );
		ini_set( 'display_errors' , 'On' );
		set_error_handler( array( $this , "errorHandler" ) , E_ALL );
	}
	
	public function errorHandler( $code , $message , $file , $line ) {
		switch( $code ) {
			case E_USER_ERROR:
				$friendly				= _("Something went terribly wrong, we have been notified of this issue.");
				break;
			case E_USER_WARNING:
				$friendly				= _("Something went wrong, we have been notified of this issue.");
				break;
			case E_USER_NOTICE:
#				$friendly				= _("Something went wrong, as a precaution we are notified of this issue.");
				break;
			default:
#				$friendly				= _("Something went wrong, we've been notified of this issue.");
		}
		
		if( HYN_DEBUG ) {
#			$message					= $message . '<span class="ui-silk ui-silk-error" class="toggler" href="errordebug-'.$this -> number.'"></span><div tab="errordebug-'.$this -> number.'">'.debug_backtrace().'</div>';
			$this -> add( $this -> errCode($code) , sprintf("%s @ %s on line %s" , $message , $file , $line ) , (isset($friendly) ? "error" : "info") );
		} else {
			$this -> add( $this -> errCode($code) , $friendly , "info" );
		}
		
		# [ TODO ] exit
	}
	
	static function get_instance() {
		if( is_null(self::$inst)) {
			self::$inst	= new notification();
		}
		return self::$inst;
	}
	public function init() {
		$inst		= self::get_instance();
		
	}
	
/*
	function __construct() {
		$this -> number				= 0;
		$this -> messages			= array();
		$this -> logcount			= array();
#		require_once(SYSTEM_LIB_SYSTEM.'firephp/fb.php');
		error_reporting( E_ALL | E_STRICT ); 
		ini_set('display_errors','On');
		set_error_handler( array($this,"errorHandler") , E_ALL );
		
		$this	-> debug			= (SYSTEM_DEBUG ? true : false );
		
#		if( SYSTEM_LIVE ) {
#			FB::setEnabled(false);
			if( !is_dir(SYSTEM_LOG_DIR)) {
				mkdir(SYSTEM_LOG_DIR,0777,true);
			}
			if( !is_file(DOMAIN_LOG)) {
				touch(DOMAIN_LOG);
			}
			if( !is_file(DOMAIN_CRITICAL_LOG)) {
				touch(DOMAIN_CRITICAL_LOG);
			}
#		}
	}
	function add( $title , $message , $type='info' ) {
		global $u;
		# if content is clean; do something else with debugging!
		# if admin of domain - show errors
		# if system admin - show debug
		$log			= array('debug');
		$err			= array('error');
		$errmsg			= date("Y-m-d H:i:s") ."\t". $_SERVER['REMOTE_ADDR'] ."\t".$type."@".$title."\t".$message." (".REQUEST_URI.")";
		if( isset( $_SERVER['HTTP_REFERER'] )) {
			$errmsg		.= "\t(".$_SERVER['HTTP_REFERER'].")";
		}
		$errmsg			.= "\r\n";
		$logforclasses	= array( "visitor" , "template" , "content" , "rohynautoload" );
		if( in_array( $message , $this -> messages )) { $this -> logcount[array_search($message,$this -> messages)]++; return; }
#		if( in_array($title,$logforclasses)) { return; }
		if( in_array( $type , $log )) {
			if( SYSTEM_DEBUG && in_array($title,$logforclasses) ) {
				error_log($errmsg,3,DOMAIN_LOG);
			}
		} elseif(in_array( $type , $err) && (isset($u) && !$u -> isSystemUser())) {
			error_log($errmsg,3,DOMAIN_CRITICAL_LOG);
		} else {
			error_log($errmsg,3,DOMAIN_LOG);
			$this -> messages[]		= $message;
			$this -> logcount[array_search($message,$this -> messages)]	= 1;
			$this -> m[$type][]		= array( 	"title" 		=> $title , 
												"notification" 	=> $message,
												"errmsg"		=> $errmsg
			);
		}
		if( SYSTEM_DEBUG && $u && $u -> isSystemAdmin() ) {
			debug_backtrace();
		}
	}
	function errCode( $code ) {
		switch($code) {
			case E_USER_ERROR:
				return "Error";
				break;
			case E_USER_WARNING:
			return "Warning";
				break;
			case E_USER_NOTICE:
				return "Notice";
			default:
				return "Other";
		}
	}
	function errorHandler( $code , $message , $file , $line ) {
		$this -> number++;
		switch( $code ) {
			case E_USER_ERROR:
				$friendly				= _("Something went terribly wrong, we've been notified of this issue.");
				break;
			case E_USER_WARNING:
				$friendly				= _("Something went wrong, we've been notified of this issue.");
				break;
			case E_USER_NOTICE:
#				$friendly				= _("Something went wrong, as a precaution we are notified of this issue.");
				break;
			default:
#				$friendly				= _("Something went wrong, we've been notified of this issue.");
		}
		if( $this -> debug ) {
#			$message					= $message . '<span class="ui-silk ui-silk-error" class="toggler" href="errordebug-'.$this -> number.'"></span><div tab="errordebug-'.$this -> number.'">'.debug_backtrace().'</div>';
			$this -> add( $this -> errCode($code) , sprintf("%s @ %s on line %s" , $message , $file , $line ) , (isset($friendly) ? "error" : "info") );
		} else {
			$this -> add( $this -> errCode($code) , $friendly , "info" );
		}
		
		if( $code == E_USER_ERROR ) { exit; }
	}*/
}
