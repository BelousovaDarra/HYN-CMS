<?PHP
if( !defined("HYN")) { exit; }

anewt_include( "xhtml" );

class DOM {
	static protected $dom	= null;
	static public function get_instance() {
		if( is_null(self::$dom) ) {
			self::$dom		= new DOM;
		}
		return self::$dom;
	}
	
	static public function set_meta( $meta , $field , $value ) {
		$dom				= self::get_instance();
		$dom		-> meta[$meta][$field]	= $value;
		
	}
	static public function set_css( $css ) {
		$dom				= self::get_instance();
		$dom		-> css[]	= $css;
		$dom		-> css		= array_unique( $dom -> css , SORT_STRING );
	}
	static public function set_js( $js ) {
		$dom				= self::get_instance();
		$dom		-> js[]		= $js;
		$dom		-> js		= array_unique( $dom -> js , SORT_STRING );
	}
	private function __construct() {
	
	}
	
}