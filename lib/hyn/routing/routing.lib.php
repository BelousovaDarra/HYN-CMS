<?PHP
if( !defined("HYN")) { exit; }

class routing {

	static private $r					= null;
	static private $c					= null;
	static private $f					= null;

	private function __construct() {
		$this			-> request_uri		= HYN_URI_REQUEST;
		$this			-> uri			= parse_url( HYN_URI_REQUEST );
		$this			-> path			= explode( "/" , $this -> uri['path'] );
		# due to explosion, the first part is empty uri /subdir
		array_shift($this -> path);
		$this			-> route		= routes::routefrompath( $this -> path );
		# no route in database found find routing from file
		if( !$this -> route && count( $this -> path ) ) {
			$this 		-> route		= $this -> findFolderRouting( $this -> path );
		}
	}
	# [TODO] find file based routings
	private function findFolderRouting( $path ) {
		# not using $this -> path because we do not want to change it
		$module							= array_shift( $path );
		if( isset($module) && _v( $module , "string" )) {
			# routing can only be located in the module dir
			if( $r = _filefind( "routing" , HYN_MS_DIR_MODULES . $module . DS , ".twig" )) {
				
			}
		}
		return false;
	}
	static function get_instance() {
		if( is_null(self::$r)) {
			self::$r	= new routing();
		}
		return self::$r;
	}

	/**
	*	Now load Module based on mapping
	*		- if necessary do SSL , LOGIN and other checks/redirects
	*/
	static function init() {
		$r					= self::get_instance();
		$call_class			= false;
		$call_func			= false;
		// find class - first by route
		if( $r -> route && class_exists( $r -> route -> get("module") ) ) {
			$call_class		= $r -> route -> get("module");
			$call_func		= $r -> route -> get("function");

		}
		// now check for uri based on class, function
		elseif( $r -> uri['path'] != "/" && count( $r -> path ) && class_exists( $r -> path[0] ) ) {
			$call_class		= $r -> path[0];
			if( isset($r -> path[1]) && method_exists( $call_class , $r -> path[1] ) ) {
				$call_func	= $r -> path[1];
			}
		}
		// now if not root page, show 404 etc
		elseif( $r -> uri['path'] != "/" && count( $r -> path ) && class_exists( "ErrorPage" ) ) {
			$call_class		= "ErrorPage";
		}
		// otherwise show overview, if available
		elseif( class_exists( "Overview" )) {
			$call_class		= "Overview";
		}
		// otherwise throw error
		else {
			// [TODO] throw error
		}

		$c					= new $call_class;
		
		
		global $MultiSite,$SiteVisitor;
		// verify SSL and LOGIN
		if( $MultiSite -> get("ssl") && HYN_URI_HTTPS != "https://" && $c -> _SSL_() ) {
			_p_redirect( "https://" . HYN_URI_HTTPHOST . HYN_URI_REQUEST );
		}
		/**
		*	check whether module requires log on and if so, redirect to login page
		* 	@todo get login page from routes.db if set, otherwise disallow access
		*/
		if( 
			// check for site user
			($c -> _LOGIN_() && !$SiteVisitor -> user) 
			// check for system user
			|| ($c -> _LOGIN_() == "system" && $SiteVisitor -> user -> get('type') != 0)
			// check for system admin
			|| ($c -> _LOGIN_() == "admin" && $SiteVisitor -> user -> get('type') != 0)  
			|| ($c -> _LOGIN_() == "admin" && !$SiteVisitor -> user -> get('admin') )  
		) {
			_p_redirect( "/login?return=" . urlencode( $_SERVER['REQUEST_URI'] ) );
		}
		// end of verify SSL and LOGIN
		/**
		*	Load bootstrap if set in settings
		*/
		if( HYN_DEBUG && $MultiSite -> setting( "bootstrap" , "core" )) {
			DOM::set_default_theme();
		}		
		// continue loading class and function
		$r -> called		= strtolower($call_class);
		self::$c			= $c;
		// find function - first by defined, if
		if( $call_func && method_exists( $c , $call_func ) && is_callable( array( $c , $call_func ))) {
			self::$f		= $call_func;
			return;
		}
		// otherwise find display func
		elseif( method_exists( $c , "display" )) {
			self::$f		= "display";
			return;
		}
	}
	static function flush() {
		$r 					= self::get_instance();
#_debug( self::$c );
		if( !isset( self::$c ) || !is_object( self::$c )) {
			if( HYN_DEBUG ) {
				exit(sprintf("<h1>System malfunctioned</h1><pre>No class found to load module from: %s - %s</pre>.",__FILE__,__LINE__));
			}
			exit(sprintf("<pre>System error, our apologies for the inconvenience [%s].</pre>",__LINE__));
		}
		if( isset(self::$f)) {
			$f			= self::$f;
		}
		DOM::parse_js();
		DOM::parse_css();
#		if( method_exists( $r -> c , "get_header" )) {
#			echo $r -> c -> get_header();
#		}
		if( isset($f)) {
			echo self::$c -> $f();
		}
#		if( method_exists( $r -> c , "get_footer" )) {
#			echo $r -> c -> get_footer();
#		}
	}
}