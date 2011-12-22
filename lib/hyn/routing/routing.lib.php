<?PHP
if( !defined("HYN")) { exit; }

class routing extends Container {
	static public $r;
	static function init() {
		$r 			= new routing;
		$r			-> set( "request_uri"	, HYN_URI_REQUEST );
		$r			-> uri			= parse_url( HYN_URI_REQUEST );
		$r			-> route		= routes::routefrompath($r -> uri['path']);
		self::$r		= $r;
		self::startupModule();
	}
	static function get_instance() {
		return self::$r;
	}

	/**
	*	Now load Module based on mapping
	*		- if necessary do SSL , LOGIN and other checks/redirects
	*/
	static function startupModule() {
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
				# start building DOM content using DOM class
			}
		}
	}
}