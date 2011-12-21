<?PHP
if(!defined("HYN")) { exit; }

spl_autoload_register( "hynautoload" );
function hynautoload($class) {
	// verify modules - local dir of domain
	if( defined("HYN_MS_DIR_MODULES") ) {
		if( is_file( HYN_MS_DIR_MODULES . $class . DS . "main.lib.php" )) {
			require_once HYN_MS_DIR_MODULES . $class . DS . "main.lib.php";
			return true;
		}
		if( is_file( HYN_MS_DIR_MODULES . $class . DS . $class . ".php" )) {
			require_once HYN_MS_DIR_MODULES . $class . DS . $class . ".php";
			return true;
		}
		if( is_file( HYN_MS_DIR_MODULES . $class . DS . $class . ".lib.php" )) {
			require_once HYN_MS_DIR_MODULES . $class . DS . $class . ".lib.php";
			return true;
		}
	}
	// verify modules - system dir
	if( true ) {
		if( is_file( HYN_PATH_HYN . $class . DS . "main.lib.php" )) {
			require_once HYN_PATH_HYN . $class . DS . "main.lib.php";
			return true;
		}
		if( is_file( HYN_PATH_HYN . $class . DS . $class . ".php" )) {
			require_once HYN_PATH_HYN . $class . DS . $class . ".php";
			return true;
		}
		if( is_file( HYN_PATH_HYN . $class . DS . $class . ".lib.php" )) {
			require_once HYN_PATH_HYN . $class . DS . $class . ".lib.php";
			return true;
		}
	}
	return false;
}
