<?PHP

if(!defined("HYN")) { exit; }


class cp extends module {
	public function _cp() {
		

		# we do a redirection after saving through post
		$this -> redir						= false;
		
		$this -> getModulesCP();
		
		$this -> baseURI					= $this -> route -> route -> get("route");
		preg_match( sprintf("/^%s(\/)?([^\/]+)(\/)?([^\/]+)?/i",str_replace("/","\/",$this -> baseURI)) , $this -> route -> request_uri , $m );
		$this -> act						= isset($m[2]) ? $m[2] : false;
		$this -> item						= isset($m[4]) ? $m[4] : false;

		if( method_exists( $this , $this -> act )) {
			call_user_func( array( $this , $this -> act ) );
		}



		if( $this -> redir ) _p_redirect( $this -> route -> request_uri );
		
	}
	private function channels() {
	}
	private function users() {
		$this -> users 						= SiteUser::find_all();
	
	}
	private function settings() {
		if( GPC::post_string( "save-settings") && $this -> item && GPC::post_string( "settings" ) ) {
			$c							= $this -> modules[$this -> item];
			$settings						= $c -> cp -> settings();
			foreach( GPC::post_string( "settings" ) as $name => $value ) {
				if( _v($value,$settings[$name]['validates'])) {
					SaveSiteSetting( $name , $this -> item , _p_value( $value , $settings[$name]['saveas']) );
					$this -> redir			= true;
				} else {
					$this -> error[]		= _(sprintf("field %s did not validate as a type %s",$settings[$name]['name'],$settings[$name]['type']));
				}
			}
		}
	}
	private function routes() {
		if( GPC::post_string( "add-route" ) ) {
			$r								= routes::find_one_by_column( "route" , GPC::post_string( "route" ));
			if( !$r ) 
			$r								= new routes;
			$r -> set( "route" 	, GPC::post_string( "route" ));
			$r -> set( "module"	, GPC::post_string( "module" ));
			$r -> set( "function"	, GPC::post_string( "function" , "display" ));
			$r -> set( "active" 	, GPC::post_bool( "active" , true ) );
			$r -> save( );
			$this -> redir							= true;
		}
		if( GPC::post_string( "del-route" )) {
			$r								= routes::find_one_by_column( "route" , GPC::post_string( "del-route" ));
			$r -> delete();
			$this -> redir							= true;
		}
		DOM::add_js( '
				jQuery( ".route-edit").click(function() {
					jQuery( this ).closest( "tr" ).children( "td[class]" ).each( function( i , elm ) {
						var inputname	= jQuery(this).attr("class");
						var inputvalue	= jQuery(this).find("code").html();
						if( inputname == "module" ) {
							jQuery( "#add-route form" ).find( "select[name=" + inputname + "] option[value!=" + inputvalue + "]").removeAttr( "selected" );
							jQuery( "#add-route form" ).find( "select[name=" + inputname + "] option[value=" + inputvalue + "]").attr( "selected" , true );
						} else {
							jQuery( "#add-route form" ).find( "input[name=" + inputname + "]" ).attr( "value" , inputvalue );
						}
					});
					jQuery( "#add-route" ).modal( "show" );
				});
				jQuery( ".route-del" ).click(function() {
					event.preventDefault();
					if(confirm(\'This will permanently remove this route, resulting in any requests to this route to re-route or fail.\')) {
						jQuery( this ).parent("form").submit(); 
					}
				});
		' , "body" );
	}
	public function display() {
		
		$vars			= array(
			"modules"	=> $this -> modules,
			"baseuri"	=> $this -> baseURI,
			"act"		=> $this -> act
		);

		switch( $this -> act ) {
			case "settings":
				if( !$this -> item ) { _p_redirect( $this -> baseURI ); }
				$vars['item']		= $this -> modules[$this -> item];
				$vars['class']		= $this -> item;
				$vars['routes']		= routes::find_by_column( "module" , $this -> item );
				return $this -> parseTemplate( "cp-module-settings" , $vars );
				break;
			case "routes":
				$vars['routes']		= routes::find_all();
				return $this -> parseTemplate( "cp-core-routes" , $vars );
				break;
			case "users":
				$vars['users']		= $this -> users;
				return $this -> parseTemplate( "cp-core-users" , $vars );
				break;
			case "channels":
				$vars['channels']	= "";
				return $this -> parseTemplate( "cp-core-channels" , $vars );
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
		
		$ms					= modules::find_by_sql(sprintf("WHERE routable >= %d",0));
		foreach( $ms as $module ) {
			// local modules are stored in domain dir instead of global dir
			if( $module -> get("local") && !$module -> get("cp") ) continue;
			$class				= $module -> get("folder");
			$this -> modules[$class]	= $module;
		}

	}
	function _SSL_() {
		return true;
	}
	function _LOGIN_() {
		return "system";
	}
}