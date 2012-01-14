<?PHP
if( !defined("HYN")) { exit; }
		# <domain>/templates/module/<file>
		# <domain>/templates/<file>
		# <system>/templates/<file>
		# <system>/lib/modules/<module>/templates/<file>
function filefind( $tpl , $module=false ) {
	if( is_file( $tpl )) { return $tpl; }	

	if( defined("HYN_MS_DIR_TPL") && is_dir( HYN_MS_DIR_TPL ) ) {
		# check site module dir
		if( $module ) {
			
			# <domain>/templates/<module>/<name>(.twig)
			if( $r = _filefind( $tpl , HYN_MS_DIR_TPL . $module . DS , "twig" )) {
				return $r;
			} else
			# <domain>/templates/<module>_<name>(.twig)
			if( $r = _filefind( $module . "_" . $tpl , HYN_MS_DIR_TPL , "twig" )) {
				return 	$r;
			}
		}
		# check site template dir with
		# <domain>/templates/<name>(.twig)
		if( $r = _filefind( $tpl , HYN_MS_DIR_TPL , "twig")) {
			return 	$r;
		}
		# check system module dir
		if( $module ) {
			# <system>/lib/modules/<module>/templates/<name>(.twig)
			if( $r = _filefind( $tpl , HYN_PATH_MODULES . $module . DS . "templates" . DS , "twig" )) {
				return $r;
			} else
			# <system>/lib/modules/<module>/<name>(.twig)
			if( $r = _filefind( $tpl , HYN_PATH_MODULES . $module . DS , "twig" )) {
				return $r;
			}
		}
		# check system module dir
		if( $module ) {
			# <system>/templates/<module>/<name>(.twig)
			if( $r = _filefind( $tpl , HYN_PATH_TPL . $module . DS , "twig" )) {
				return $r;
			}
		}
		# check system template dir
		# <system>/templates/<name>.twig
		if( $r = _filefind( $tpl , HYN_PATH_TPL , "twig" )) {
			return $r;
		}
		return false;
	}
}
function _filefind( $file , $dir , $ext=false ) {
	if( $ext ) {
		if( is_dir( $dir ) && is_file( $dir . $file . "." . $ext ) ) {
			return $dir . $file . "." . $ext;
		}
	}
	if( is_dir( $dir ) && is_file( $dir . $file ) ) {
		return $dir . $file;
	}
	return false;
}