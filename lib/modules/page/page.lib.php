<?PHP
if( !defined("HYN")) { exit; }

class page extends module {
	public function _page() {
		$this -> path		= $this -> route -> path;
		$this -> page		= pages::find_one_by_route( $this -> path );
		$leftovers			= str_replace( ($this -> page ? $this -> page -> route : ''), '' ,  '/'.implode('/',$this -> path) );
		if( strlen( $leftovers) > 1  && preg_match( "/([a-z])/i" , $leftovers )) {
			$this -> page -> notexactsame	= $leftovers;	
			global $MultiSite;
			if( $MultiSite -> get('isowner') ) {
				DOM::add_js("
				// create a new page
				jQuery('*[data-new-href]').on('click', function() {
					jQuery.ajax({
						cache:				false,
						type:				'GET',
						url:				jQuery(this).attr('data-new-href'),
						success:			function(route) {
							window.location	=  route;
						}
					});
					return false;
				});
				","body"
				);
			}
			
			
		} else { $this -> page -> notexactsame	= false; }
	}
	public function ajax() {
		global $MultiSite;
		if( !$MultiSite -> isowner ) { exit; }
		list( $t , $m , $id , $type ) 	= $this -> route -> path;
		if( $id == "new" ) {
			$route						= array_slice( $this -> route -> path , 3 );
			if( end($route) == "" ) { array_pop( $route ); }
			$route						= '/'.implode("/",$route);
			if( pages::find_one_by_column( "route" , $route )) {
				exit(0);
			}
			$this -> page				= new pages;
			$this -> page -> set("route" , $route );
			$this -> page -> set("created",AnewtDatetime::now());
			$this -> page -> set("updated",AnewtDatetime::now());
			$this -> page -> set("title" , "Example title" );
			$this -> page -> set("subtitle", "and a subtitle");
			$this -> page -> set("content","Cupcake ipsum dolor sit amet. Gingerbread lollipop sesame snaps tiramisu muffin oat cake. Topping pastry marzipan. Wafer sugar plum sweet jelly beans marshmallow jujubes.");
			$this -> page -> save(true);
			exit($this -> page -> get("route").'/');
		}
		$this -> page		= pages::find_one_by_id( $id );
		if( GPC::post_string( "newcontent") ) {
			$new			= GPC::post_string( "newcontent");
			if( $type == "content" ) {
				$this -> page -> set("content",$new);
				$this -> page -> save();
			} elseif( $type == "title" ) {
				$regex	= "/<small>(.*)<\/small>/i";
				preg_match( $regex , $new , $m );
				# subtitle exists
				if( count($m) ) {
					$title		= str_replace( $m[0] , "" , $new );
					$subtitle	= trim($m[1]);
					$this -> page -> set("subtitle" , $subtitle);
				} else {
					$title		= $new;
				}
				$title			= trim( $title );
				$this -> page -> set("title",$title);
			}
			# throw error 0 if type does not exist
			else {
				exit(json_encode(
					array("error" => _("This type is unknown for the pages module."))
				));
			}
			$this -> page -> set( "updated" , AnewtDatetime::now() );
			$this -> page -> save();
			exit;
		}
		exit();
	}
	public function display() {
		if( !$this -> page && !$this -> page -> notexactsame ) {
			# if admin
			#DOM::set_wysiwyg();
			
			# [TODO] show 404
			hyn_include( "errorpage" );
			$e		= new ErrorPage;
			return $e -> display();
		}
		global $MultiSite;
		if( $MultiSite -> get("isowner") ) {
			DOM::set_wysiwyg();
		}
		DOM::set_title( 
				trim($this -> page -> title) . 
				( $this -> page -> subtitle && strlen( $this -> page -> title) < 100 
					? " - " . $this -> page -> subtitle 
					: false 
				)
		);
		return $this -> parseTemplate( "page" , array( "page" => $this -> page ));
	}
}
