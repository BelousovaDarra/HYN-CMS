<?PHP
if(!defined("HYN")) { exit; } 

/**
*	Class module to built new modules
*
*		modules must extend this class
*/
class module extends Container {
	/**
	*	
	*
	*/
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
	}
	final private function setupDB() {
		$this -> db			= AnewtDatabase::get_connection( "default" );
	}
	final private function setupTwig() {
		hyn_include( "twig" );
		if( defined("HYN_MS_DIR_TPL")) {
			$tpldirs[]		= HYN_MS_DIR_TPL;
		}
		$tpldirs[]			= HYN_PATH_TPL;
		$twigloader			= new Twig_loader_Filesystem( $tpldirs );
		$this -> twig		= new Twig_Environment( $twigloader );	
	}
	final public function parseTemplate( $tpl , $vars=false ) {

	}
}