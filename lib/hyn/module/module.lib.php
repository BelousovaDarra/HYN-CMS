<?PHP
if(!defined("HYN")) { exit; } 

/**
*	Class module to built new modules
*
*		modules must extend this class
*/
abstract class module {
	/**
	*	
	*
	*/
	

	final public function __construct() {
		global $MultiSite,$SiteVisitor;
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
		$this -> setupRoutes();
		$this -> pathid				= $this -> get_path_id();
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
	public function _SSL_() {
		return false;
	}
	/**
	*	if module returns "site" or true, will check whether a user is logged in
	*	if module returns "system", will check whether system user is logged in
	*	if module returns "admin", will check whether admin system user is logged in
	*	if not any of both requirements are met, visitor is redirect to login page
	*	after login, visitor is redirect back to where he came from
	*/
	public function _LOGIN_() {
		return false;
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
	final private function setupRoutes() {
		$this -> route			= routing::get_instance();
	}
	final private function setupDB() {
		if( isset($this -> db) ) return;
		$this -> db			= AnewtDatabase::get_connection( "default" );
	}
	final private function setupTwig() {
		if( isset($this -> twig) ) return;
		$this -> twig			= Twig::get_instance();
	}
	final public function parseTemplate( $tpl , $vrs=false ) {
		return Twig::parse( filefind($tpl,$this -> class) , (_v($vrs,"array") ? $vrs : array()) );
	}
	public function get_header() {
		return $this -> parseTemplate( "header" );
	}
	public function get_footer() {
		return $this -> parseTemplate( "footer" );
	}
	private function get_path_id() {
		$r		= $this -> route -> path;
		$set	= false;
		if( _v($r,"array")) { foreach( $r as $i ) {
			if( !$set && _v($i,"integer")) {
				$set				= (int) $i;
			}
		}}
		return $set;
	}
	private function loadSettings() {
		
	}
}