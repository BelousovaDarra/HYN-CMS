<?PHP
if( !defined("HYN")) { exit; }

class routing {
	static private $r	= null;
	private function __construct() {
#		$r 			= self::get_instance();
		$this			-> request_uri		= HYN_URI_REQUEST;
		$this			-> uri			= parse_url( HYN_URI_REQUEST );
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
		$r			= self::get_instance();
		$call_class		= false;
		$call_func		= false;
		if( $r -> route ) {
			$call_class	= $r -> route -> get("module");
			$call_func	= ( $r -> route -> get("function") ? $r -> route -> get("function") : "display" );
		}
		if( $call_class && is_callable( $call_class )) {
			$c		= new $call_class;
			if( $c && $call_func && method_exists( $c , $call_func )) {
				$r -> c		= $c;
				$r -> f		= $call_func;
			}
		} elseif( $r -> uri['path'] != "/" ) {			// let's assume user wanted to visit something, which is not there - 404
#[TODO]			
		} else {						// let's assume user visits the / root path
			$r -> c			= new Overview;
			$r -> f			="display";
		}
	}
	static function flush() {
		$r 			= self::get_instance();
		if( !isset( $r -> c ) || !is_object( $r -> c )) {
			if( HYN_DEBUG ) {
				exit(sprintf("<h1>System malfunctioned</h1><pre>No class found to load module from: %s - %s</pre>.",__FILE__,__LINE__));
			}
			exit(sprintf("<pre>System error, our apologies for the inconvenience [%s].</pre>",__LINE__));
		}
		if( isset($r -> f) ) {
			$f		= $r -> f;
			$content	= $r -> c -> $f();
		}
		DOM::parse_js();
		DOM::parse_css();
		if( method_exists( $r -> c , "get_header" )) {
			echo $r -> c -> get_header();
		}
		if( isset($content)) {
			echo $content;
		}
		if( method_exists( $r -> c , "get_footer" )) {
			echo $r -> c -> get_footer();
		}
	}
}