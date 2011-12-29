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
			if( is_dir( HYN_MS_DIR_TPL . $module ) && is_file( HYN_MS_DIR_TPL . $module . DS . $tpl . ".twig" )) {
				return 	HYN_MS_DIR_TPL . $module . DS . $tpl . ".twig";
			} elseif( is_dir( HYN_MS_DIR_TPL . $module ) && is_file( HYN_MS_DIR_TPL . $module . DS . $tpl ) ) {
				return 	HYN_MS_DIR_TPL . $module . DS . $tpl;
			} elseif( is_dir( HYN_MS_DIR_TPL ) && is_file( HYN_MS_DIR_TPL . $module . "_" . $tpl . ".twig" ) ) {
				return 	HYN_MS_DIR_TPL . $module . "_" . $tpl . ".twig";
			} elseif( is_dir( HYN_MS_DIR_TPL ) && is_file( HYN_MS_DIR_TPL . $module . "_" . $tpl ) ) {
				return 	HYN_MS_DIR_TPL . $module . "_" . $tpl;
			}
		}
		# check site template dir with
		if( is_file( HYN_MS_DIR_TPL . $tpl . ".twig" )) {
			return 	HYN_MS_DIR_TPL .  $tpl . ".twig";
		} elseif( is_file( HYN_MS_DIR_TPL . $tpl ) ) {
			return 	HYN_MS_DIR_TPL . $tpl;
		}
		# check system module dir
		if( $module ) {
			if( is_dir( HYN_PATH_MODULES . $module . DS . "templates" ) && is_file( HYN_PATH_MODULES . $module . DS . "templates" . DS . $tpl . ".twig" )) {
				return HYN_PATH_MODULES . $module . DS . "templates" . DS . $tpl . ".twig";
			} elseif( is_dir( HYN_PATH_MODULES . $module . DS . "templates" ) && is_file( HYN_PATH_MODULES . $module . DS . "templates" . DS . $tpl )) {
				return HYN_PATH_MODULES . $module . DS . "templates" . DS . $tpl;
			} elseif( is_dir( HYN_PATH_MODULES . $module ) && is_file( HYN_PATH_MODULES . $module . DS . $tpl . ".twig" )) {
				return HYN_PATH_MODULES . $module . DS . DS . $tpl . ".twig";
			} elseif( is_dir( HYN_PATH_MODULES . $module ) && is_file( HYN_PATH_MODULES . $module . DS . $tpl )) {
				return HYN_PATH_MODULES . $module . DS . $tpl;
			}
		}
		# check system template dir
		if( is_dir( HYN_PATH_TPL ) && is_file( HYN_PATH_TPL . $tpl . ".twig")) {
			return HYN_PATH_TPL . $tpl . ".twig";
		} elseif( is_dir( HYN_PATH_TPL ) && is_file( HYN_PATH_TPL . $tpl )) {
			return HYN_PATH_TPL . $tpl;
		}
		return false;
	}
}