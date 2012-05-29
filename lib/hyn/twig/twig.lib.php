<?PHP
if( !defined("HYN")) { exit; }

class Twig {
	
	static $twig		= null;
	private static $firstrun		= false;
	
	private function __construct() {
		$l				= new Twig_Hyn_Loader();
		$this -> twig			= new Twig_Environment( $l );
		
		// add functions (NOT methods) to twig
		$twigfunctions			= array(
			"Function" 	=> array(
									"_"				=> "_",
									"sitesetting"	=> "SiteSetting",
									"_debug"		=> "_debug",
									"_p_money"		=> "_p_money",
			),
			"Filter" 	=> array(
									"bytes"			=> "_p_bytes",
									"count"			=> "count"
			)
		);
		foreach( $twigfunctions as $twigimplement => $fs ) {
			foreach( $fs as $twigf => $cmsf ) {
				$addFunction			= "add".$twigimplement;
				$addTwigFunction		= "Twig_".$twigimplement."_Function";
				$this -> twig -> $addFunction( $twigf , new $addTwigFunction( $cmsf ) );	
			}
		}
	}
	static function get_instance() {
		if( is_null( self::$twig )) {
			self::$twig		= new Twig;
		}
		return self::$twig;
	}
	static public function parse( $file , $vrs=false ) {
		$t				= self::get_instance();
		return $t -> _parse( $file , $vrs );
	}
	private function _parse( $file , $vrs=false ) {
		if( !$vrs ) { $vrs	= array(); }
		if( !self::$firstrun ) {
			$this -> setupGlobalVars();
			self::$firstrun	= true;
		}

		$vars			= array_merge( $vrs , $this -> vars );	
		return $this -> twig -> render( $file , (_v($vars,"array") ? $vars : array()) );
	}
	final private function setupGlobalVars() {
		global $MultiSite,$SiteVisitor;
		$this -> vars['ms']			= $MultiSite;
		if( MultiSite::setting( "tracking-id" , "google-analytics" ) ) {
			$this -> vars['ga']		= MultiSite::setting( "tracking-id" , "google-analytics" ) -> get('value');
		}

		$this -> vars['dom']		= DOM::get_instance();
		$this -> vars['globals']	= $_SERVER;
		$this -> vars['visitor']	= $SiteVisitor;

		$cp									= routes::find_one_by_column( "module" , "cp" );
		$this -> vars['_routes']['cp']
									= ( $cp ? $cp -> route : false );
		
		if( isset($_POST)) {
			$this -> vars['globals']['post']	= $_POST;	
		}
		if( isset($_GET)) {
			$this -> vars['globals']['get']	= $_GET;
		}
	}
	static function addVar( $var , $value ) {
		$t							= self::get_instance();
		$t -> vars[$var]			= $value;
	}
}