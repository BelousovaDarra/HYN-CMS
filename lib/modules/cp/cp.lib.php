<?PHP

if(!defined("HYN")) { exit; }


class cp extends module {
	public function _cp() {
		$this -> getModulesCP();
		
		$this -> baseURI					= $this -> route -> route -> get("route");
		preg_match( sprintf("/^%s(\/)?([^\/]+)(\/)?([^\/]+)?/i",str_replace("/","\/",$this -> baseURI)) , $this -> route -> request_uri , $m );
		$this -> act						= isset($m[2]) ? $m[2] : false;
		$this -> item						= isset($m[4]) ? $m[4] : false;
		
		$this -> handleGPC();
		
	}
	private function handleGPC() {
		$redir								= false;
		if( $this -> act == "settings" && GPC::post_string( "save-settings") && $this -> item && GPC::post_string( "settings" ) ) {
			$c								= $this -> classes[$this -> item];
			$settings						= $c -> settings();
			foreach( GPC::post_string( "settings" ) as $name => $value ) {
				if( _v($value,$settings[$name]['validates'])) {
					SaveSiteSetting( $name , $this -> item , _p_value( $value , $settings[$name]['saveas']) );
					$redir					= true;
				} else {
					$this -> error[]		= _(sprintf("field %s did not validate as a type %s",$settings[$name]['name'],$settings[$name]['type']));
				}
			}
		}
		if( $this -> act == "routes" && GPC::post_string( "add-route" ) ) {
			$r								= routes::find_one_by_column( "route" , GPC::post_string( "route" ));
			if( !$r ) 
			$r								= new routes;
			$r -> set( "route" 		, GPC::post_string( "route" ));
			$r -> set( "module"		, GPC::post_string( "module" ));
			$r -> set( "function"	, GPC::post_string( "function" , "display" ));
			$r -> set( "active" 	, GPC::post_bool( "active" , true ) );
			$r -> save( );
			$redir 							= true;
		}
		
		if( $redir ) _p_redirect( sprintf( "%s/%s" ,$this -> baseURI , $this -> act ));
	}
	public function display() {
		
		$vars			= array(
			"modules"	=> $this -> modules,
			"classes"	=> $this -> classes,
			"baseuri"	=> $this -> baseURI,
			"act"		=> $this -> act
		);

		switch( $this -> act ) {
			case "settings":
				if( !$this -> item ) { _p_redirect( $this -> baseURI ); }
				$vars['item']		= $this -> classes[$this -> item];
				$vars['class']		= $this -> item;
				$vars['routes']		= routes::find_by_column( "module" , $this -> item );
				return $this -> parseTemplate( "cp-module-settings" , $vars );
				break;
			case "routes":
				$vars['routes']		= routes::find_all();
				return $this -> parseTemplate( "cp-core-routes" , $vars );
				break;
			case false:
				break;
			default:
				_p_redirect( $this -> baseURI );
		}
		return $this -> parseTemplate( "cp-index"  , $vars );
	}
	
	/**
	*	@return list of all modules with <plugin>.cp.php file
	*	@note should also include domain modules, first global than overrule with domain
	*/
	private function getModulesCP() {
		$this -> modules 					= array();
		
		$modirs		= scandir( HYN_PATH_MODULES );
		
		foreach( $modirs as $dir ) {
			if( $dir == "." || $dir == ".." || $dir == "cp" ) {
				continue;
			}
			if( is_file( HYN_PATH_MODULES . $dir . DS . $dir . ".cp.php" )) {
				$this -> modules[$dir]		= HYN_PATH_MODULES . $dir . DS;
			}
		}
		if( HYN_MS_DIR_MODULES ) {
			$modirs	= scandir( HYN_MS_DIR_MODULES );
			if( isset($modirs) && _v($modirs,"array") ) {
				foreach( $modirs as $dir ) {
					if( is_file( HYN_MS_DIR_MODULES . $dir . DS . $dir . ".cp.php" )) {
						$this -> modules[$dir]	= HYN_MS_DIR_MODULES . $dir . DS;
					}
				}
			}
		}
		$this -> classes					= array();
		foreach( $this -> modules as $class => $dir ) {
			// require the cp lib file for this module
			if( is_file( sprintf( "%smain.lib.php" , $dir ))) {
				require_once sprintf( "%smain.lib.php" , $dir );
			}
			if( is_file(sprintf( "%s%s.cp.php" , $dir , $class ))) {
				require_once sprintf( "%s%s.cp.php" , $dir , $class );
			} else { continue; }
			
			// instantiate a class for later calling
			$c								= sprintf( "%sCP" , $class );
			$this -> classes[$class]		= new $c;
		}
	}
	function _SSL_() {
		return true;
	}
	function _LOGIN_() {
		return "system";
	}
}