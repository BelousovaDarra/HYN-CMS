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
		$this			-> route		= routes::routefrompath($this -> uri['path']);
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
		elseif( $r -> uri['path'] != "/" && count( $this -> path ) && class_exists( $this -> path[0] ) ) {
			$call_class		= $this -> path[0];
			if( isset($this -> path[1]) && method_exists( $call_class , $this -> path[1] ) ) {
				$call_func	= $this -> path[1];
			}
		}
		// now if not root page, show 404 etc
		elseif( $r -> uri['path'] != "/" && count( $this -> path ) && class_exists( "ErrorPage" ) ) {
			$call_class		= "ErrorPage";
		}
		// otherwise show overview, if available
		elseif( class_exists( "Overview" )) {
			$call_class		= "Overview";
		}
		// otherwise throw error
		else {
			# [TODO] throw error
		}
		
		$c					= new $call_class;
		self::$c			= $c;
		// find function - first by defined, if
		if( $call_func && method_exists( $c , $call_func )) {
			self::$f		= $call_func;
			return;
		}
		// otherwise find display func
		elseif( method_exists( $c , "display" )) {
			self::$f		= "display";
			return;
		}
		
/*		if( method_exists( $call_class , $r -> route -> get("function") )) {
			$call_func	= $r -> route -> get("function");
		} elseif( method_exists( $call_class , "display")) {
			$call_func	= "display";
		}
		if( $call_class && class_exists( $call_class )) {
			$c		= new $call_class;
			if( $c && $call_func && method_exists( $c , $call_func )) {
				self::$c		= $c;
				self::$f		= $call_func;
			}
		} elseif( $r -> uri['path'] != "/" ) {			// let's assume user wanted to visit something, which is not there - 404
#[TODO]			
		} else {						// let's assume user visits the / root path
			$r -> $c			= new Overview;
			$r -> $f			="display";
		}*/
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