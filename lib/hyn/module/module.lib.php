<?PHP
if(!defined("HYN")) { exit; } 

/**
*	Class module to built new modules
*
*		modules must extend this class
*/
class module {
	/**
	*	
	*
	*/
	private static $firstrun		= false;
	final public function __construct() {
		$module			= strtolower(get_called_class());
		# subclass of a module is called
		if( strstr($module,"_")) {
			$mod		= explode( "_" , $module );
			# validate parent class as a valid, licensed module
			if( !is_callable( $mod[0] ) || !$this -> licensed( $mod[0] ) ) {
				// [TODO] error
			}
		}
		$this -> class		= $module;
		$this -> setupDB();
		$this -> setupTwig();
		# now call constructor of module class
		$callon		= "_".$this -> class;
		if( method_exists( $this , $callon ) ) {
			# read any session information
			$this -> loadModuleSession();
			$this -> $callon( (isset($moduleoptions) ? $moduleoptions : false) );
		} else {
#[TODO] error handling			
			return;
		}
	}
	protected function saveModuleSession() {
		if( isset($this -> session)) {
			$_SESSION[$this -> class]	= serialize( $this -> session );
			return true;
		} else { return false; }
	}
	protected function loadModuleSession() {
		if( isset($_SESSION[$this -> class])) {
			$this -> session			= unserialize( $_SESSION[$this -> class] );
			return true;
		} else { return false; }
	}
	final private function setupGlobalVars() {
		global $MultiSite;
		$this -> vars['ms']		= $MultiSite;
		$this -> vars['dom']		= DOM::get_instance();
	}
	final private function setupDB() {
		if( isset($this -> db) ) return;
		$this -> db			= AnewtDatabase::get_connection( "default" );
	}
	final private function setupTwig() {
		if( isset($this -> twig) ) return;
		hyn_include( "twig" );
		if( defined("HYN_MS_DIR_TPL") && is_dir( HYN_MS_DIR_TPL ) ) {
			$tpldirs[]		= HYN_MS_DIR_TPL;
		}
		$tpldirs[]			= HYN_PATH_TPL;
		$twigloader			= new Twig_Loader_Filesystem( $tpldirs );
		$this -> twig			= new Twig_Environment( $twigloader );	
	}
	final public function parseTemplate( $tpl , $vrs=false ) {
		if( !$vrs ) { $vrs	= array(); }
		if( !self::$firstrun ) {
			$this -> setupGlobalVars();
			self::$firstrun	= true;
		}
		$vars			= array_merge( $vrs , $this -> vars );
		return $this -> twig -> render( $tpl , (_v($vars,"array") ? $vars : array()) );
	}
	public function get_header() {
		return $this -> parseTemplate( "header.twig" );
	}
	public function get_footer() {
		return $this -> parseTemplate( "footer.twig" );
	}
}