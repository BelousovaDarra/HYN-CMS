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
			# <domain>/templates/<module>/<name>.twig
			if( is_dir( HYN_MS_DIR_TPL . $module ) && is_file( HYN_MS_DIR_TPL . $module . DS . $tpl . ".twig" )) {
				return 	HYN_MS_DIR_TPL . $module . DS . $tpl . ".twig";
			} else
			# <domain>/templates/<module>/<name>
			if( is_dir( HYN_MS_DIR_TPL . $module ) && is_file( HYN_MS_DIR_TPL . $module . DS . $tpl ) ) {
				return 	HYN_MS_DIR_TPL . $module . DS . $tpl;
			} else
			# <domain>/templates/<module>_<name>.twig
			if( is_dir( HYN_MS_DIR_TPL ) && is_file( HYN_MS_DIR_TPL . $module . "_" . $tpl . ".twig" ) ) {
				return 	HYN_MS_DIR_TPL . $module . "_" . $tpl . ".twig";
			} else
			# <domain>/templates/<module>_<name>
			if( is_dir( HYN_MS_DIR_TPL ) && is_file( HYN_MS_DIR_TPL . $module . "_" . $tpl ) ) {
				return 	HYN_MS_DIR_TPL . $module . "_" . $tpl;
			}
		}
		# check site template dir with
		# <domain>/templates/<name>.twig
		if( is_file( HYN_MS_DIR_TPL . $tpl . ".twig" )) {
			return 	HYN_MS_DIR_TPL .  $tpl . ".twig";
		} else
		# <domain>/templates/<name>
		if( is_file( HYN_MS_DIR_TPL . $tpl ) ) {
			return 	HYN_MS_DIR_TPL . $tpl;
		}
		# check system module dir
		if( $module ) {
			# <system>/lib/modules/<module>/templates/<name>.twig
			if( is_dir( HYN_PATH_MODULES . $module . DS . "templates" ) && is_file( HYN_PATH_MODULES . $module . DS . "templates" . DS . $tpl . ".twig" )) {
				return HYN_PATH_MODULES . $module . DS . "templates" . DS . $tpl . ".twig";
			} else
			# <system>/lib/modules/<module>/templates/<name>
			if( is_dir( HYN_PATH_MODULES . $module . DS . "templates" ) && is_file( HYN_PATH_MODULES . $module . DS . "templates" . DS . $tpl )) {
				return HYN_PATH_MODULES . $module . DS . "templates" . DS . $tpl;
			} else
			# <system>/lib/modules/<module>/<name>.twig
			if( is_dir( HYN_PATH_MODULES . $module ) && is_file( HYN_PATH_MODULES . $module . DS . $tpl . ".twig" )) {
				return HYN_PATH_MODULES . $module . DS . DS . $tpl . ".twig";
			} else
			# <system>/lib/modules/<module>/<name>
			if( is_dir( HYN_PATH_MODULES . $module ) && is_file( HYN_PATH_MODULES . $module . DS . $tpl )) {
				return HYN_PATH_MODULES . $module . DS . $tpl;
			}
		}
		# check system module dir
		if( $module ) {
			# <system>/templates/<module>/<name>.twig
			if( is_dir( HYN_PATH_TPL . DS . $module ) && is_file( HYN_PATH_TPL . DS . $module . DS . $tpl . ".twig")) {
				return HYN_PATH_TPL . $tpl . ".twig";
			} else
			# <system>/templates/<module>/<name>
			if( is_dir( HYN_PATH_TPL . DS . $module ) && is_file( HYN_PATH_TPL . DS . $module . $tpl )) {
				return HYN_PATH_TPL . $tpl;
			}
		}
		# check system template dir
		# <system>/templates/<name>.twig
		if( is_dir( HYN_PATH_TPL ) && is_file( HYN_PATH_TPL . $tpl . ".twig")) {
			return HYN_PATH_TPL . $tpl . ".twig";
		} else
		# <system>/templates/<name>
		if( is_dir( HYN_PATH_TPL ) && is_file( HYN_PATH_TPL . $tpl )) {
			return HYN_PATH_TPL . $tpl;
		}
		return false;
	}
}