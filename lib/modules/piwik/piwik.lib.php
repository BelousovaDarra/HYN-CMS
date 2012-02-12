<?PHP
if( !defined("HYN")) { exit; }



define( "PIWIK_DOCUMENT_ROOT" 		, __DIR__ . DS . "lib" . DS );
define( "PIWIK_INCLUDE_PATH" 		, PIWIK_DOCUMENT_ROOT );
define( "PIWIK_USER_PATH"		, PIWIK_DOCUMENT_ROOT );

define( "PIWIK_ENABLE_SESSION_START" 	, 0 );

require_once PIWIK_DOCUMENT_ROOT . "core" . DS . "Loader.php";
require_once PIWIK_DOCUMENT_ROOT . "core" . DS . "ErrorHandler.php";
require_once PIWIK_DOCUMENT_ROOT . "core" . DS . "ExceptionHandler.php";


class piwik extends module {
	public function _piwik() {
#		set_error_handler('Piwik_ErrorHandler');
#		set_exception_handler('Piwik_ExceptionHandler');
		$this -> c		= Piwik_FrontController::getInstance();
//		$this -> c -> init();
	}
	public function display() {
//		return $this -> c -> dispatch();
	}
}