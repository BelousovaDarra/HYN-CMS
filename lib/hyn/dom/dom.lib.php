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
	static public function set_piwik() {
		$dom				= self::get_instance();
		if( 	defined("PIWIK_TOKEN_AUTH") 
			&& PIWIK_TOKEN_AUTH != FALSE
			&& (	
				!isset( $dom -> piwik ) 
				|| ( isset($dom -> piwik) && !$dom -> piwik ) 
			)
		) {
			$dom		-> piwik
								= true;
		}		
	}
	static public function set_wysiwyg() {
		$dom				= self::get_instance();
		if( !isset( $dom -> wysiwyg ) || !$dom -> wysiwyg ) {
			$dom		-> wysiwyg
								= true;
		}
	}
	static public function set_title( $value ) {
		$dom				= self::get_instance();
		$dom		-> title
							= $value;
		
	}	
	static public function set_meta( $meta , $field , $value ) {
		$dom				= self::get_instance();
		$dom		-> meta[$meta][$field]	= $value;
		
	}
	/** 
	*		add_js allows adding parsed js code to the body execution
	*/
	static public function add_js( $js , $where="body" ) {
		$dom					= self::get_instance();
		$dom		-> parsejs[$where][]	
								= $js;
	}
	static public function set_css( $css ) {
		$dom					= self::get_instance();
		$dom		-> css[]	= $css;
		$dom		-> css		= array_unique( $dom -> css , SORT_STRING );
	}
	static public function set_js( $js , $opts=false ) {
		if( $opts ) { extract( $opts ); }
		$dom					= self::get_instance();
		$dom		-> js[]		= $js;
		$dom		-> js		= array_unique( $dom -> js , SORT_STRING );
	}
	static public function set_show_uri( $uri ) {
		$dom					= self::get_instance();
		$dom		-> show_uri	= $uri;
	}
	private function __construct() {
		$this -> css			= array();
		$this -> js				= array();
		$this -> request_uri	= HYN_URI_REQUEST;
		$this -> show_uri		= HYN_URI_REQUEST;
	}
	static public function parse_js() {
		$dom				= self::get_instance();
		if( _v($dom -> js,"array")) {
			$dom -> min( "js" );
		}
	}
	static public function parse_css() {
		$dom				= self::get_instance();
		if( _v($dom -> css,"array")) {
			$dom -> min( "css" );
		}
	}
	private function min( $type ) {
		if( $type != "js" && $type != "css" ) { return; }
		if( $type == "js" ) {
			hyn_include( "jsmin" );
			$r			= "";
			foreach( $this -> js as $i => $js ) {
				$uri		= parse_url( $js );
				# local file
				if( !isset($uri['scheme']) && !isset($uri['host'])) {
					if( preg_match( "/\.twig/i",$js) ) {
						$javascript = Twig::parse( $js );
					} else {
						$javascript	= file_get_contents( filefind($js) );
					}
					if( HYN_DEBUG ) {
						$r 	.= $javascript;
					} else {
						$r		.= JSMin::minify( $javascript );
					}
					unset( $this -> js[$i] );
				}
			}
			if( strlen($r) > 0 ) {
				file_put_contents( HYN_PATH_PUBLIC . "cache" . DS . "js". DS . md5(HYN_URI_REQUEST) . ".js" , $r );
				$this -> js[]		= "/cache/js/".md5(HYN_URI_REQUEST).".js";
			}
			// add any other scripts seperately
			if( _v($this -> parsejs,"array")) { foreach( $this -> parsejs as $where => $jsfiles ) {
				$r			= "";
				foreach( $jsfiles as $js ) {
					$r		.= JSMin::minify( $js );
//					$r		.= $js;
				}
				if( strlen($r) > 0 ) {
					// parse to body or other where; if posssible
					if( $t	= _filefind( $where , __DIR__ . DS . "js" , "twig" )) {
						$r				= Twig::parse( $t , array( "content" => $r ));
					}
					file_put_contents( HYN_PATH_PUBLIC . "cache" . DS . "js" . DS . md5( HYN_URI_REQUEST ) . "_".$where.".js" , $r );
					$this -> js[]		= "/cache/js/" . md5( HYN_URI_REQUEST) . "_".$where.".js";
				}
			}}
		} elseif( $type == "css" ) {
			$minsearch	= array("/(\t|\r|\n)+/i","/([ ]{2,})+/i","/\/\*(.*?)\*\//im","/\/\/(.*?)$/im");
			$minreplace	= array(" ","","","");
			$r			= "";
			foreach( $this -> css as $i => $css ) {
				$uri					= parse_url( $css );
				# local file
				if( !isset($uri['scheme']) && !isset($uri['host'])) {
					$stylesheet			= file_get_contents( filefind($css) );
					$r					.= preg_replace( $minsearch , $minreplace , $stylesheet );
					unset( $this -> css[$i] );
				}
			}
			if( strlen($r) > 0 ) {
				if( file_put_contents( HYN_PATH_PUBLIC . "cache" . DS ."css" . DS . md5(HYN_URI_REQUEST) . ".css" , $r )) {
					$this -> css[]		= "/cache/css/".md5(HYN_URI_REQUEST).".css";
				}
			}
		}
	}
}
