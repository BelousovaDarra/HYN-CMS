<?PHP
if( !defined("HYN")) { exit; }

hyn_include("country");
hyn_include("ecommerce");
/* we are using this to cache products and the like */
$m_adm_cache			= NULL;
global $m_adm_cache;
class administration extends module {
	
	private $tpl		= false;
	private $vars		= false;
	
	public function _administration() {
		DOM::set_default_theme();
#		DOM::set_js( "https://maps.googleapis.com/maps/api/js?key=AIzaSyA7YULvmwWO9lgoaky93cACLYN-vZac9ps&sensor=true" );
#		DOM::add_js( $this -> relation_map() );
		$this -> vars['countries']
						= Country::find_all();
		$this -> vars['orgtypes']
						= array(
						0		=> _("private person"),
						1		=> _("freelancer"),
						2		=> _("sole proprietorship"),
						3		=> _("non profit"),
						4		=> _("national firm"),
						5		=> _("international firm")
						);
		$this -> vars['states']
						= NULL;
		$this -> vars['categories']
						= productcategory::find_all();
		$this -> vars["currencies"]
						= Currencies::find_all();
		$this -> vars['billperiods']
						= array(
							"d"		=> array( 	_("day") 	, _("days") ),
							"m"		=> array(	_("month")	, _("months") ),
							"q"		=> array(	_("quarter"), _("quarters") ),
							"y"		=> array(	_("year")	, _("years") ),
		);
		global $m_adm_cache;
		$m_adm_cache['vars']	= $this -> vars;

		$this -> processGPCs();
						
		if( !isset($this -> route -> path[1])) {
			$this -> overview();
			return;
		}
		switch( $this -> route -> path[1] ) {
			case "invoice":
				$this -> invoice();
				break;
			case "relation":
				if( $this -> pathid ) {
					$relation				= relation::find_one_by_id( $this -> pathid );

					if( !is_null($relation) && $relation -> get("gmaps_address") ) {
						hyn_include( "gmaps" );
						$gm					= new Googlemaps;
						$gm -> apikey		= "AIzaSyA7YULvmwWO9lgoaky93cACLYN-vZac9ps";
//						$gm -> disableDefaultUI = true;
						$gm -> map_div_id	= "map_relation_small";
						$gm -> center		= $relation -> get("gmaps_address");
						$gm -> add_marker( array( 
								"position" 	=> $relation -> get("gmaps_address"),
								"title"		=> $relation -> get("name")
						));
						DOM::set_title( _("relation") . ": ". $relation -> get("name") );
						$gmap				= $gm -> create_map();
						$this -> vars['relationmap']
											= array( "id"	=> $gm -> map_div_id );
						
						/**
						*	Create popupable google maps
						*	@var 	.relation-location-map
						*/
/*						DOM::add_js( '
							if( jQuery(".relation-location-map").length > 0 ) {
								jQuery(".relation-location-map").click( function() {  
									jQuery("#relation-modal-map").parent(".modal").modal("show");
								});
							}
						', "body" );*/
						$this -> vars['lastinvoices']	= $relation -> get( "last_invoices" );
					}
				}
				$this -> relation( ( is_null($relation) ? false : $relation ));
				break;
			case "product":
				$this -> product();
				break;
			default:
				$this -> overview();
		}

	}
	private function processGPCs() {
		# create relation
		if( GPC::post_string("relation-change")) {
			if( GPC::post_int( "relationid" )) {
				$r		= relation::find_one_by_id( GPC::post_int( "relationid" ) );
				$save		= true;
			} else {
				$r		= new relation;
				$save		= false;
			}
			
			$post			= $_POST;
			$post['vat']		= (int) ($post['vat'] * 100);
			
			$r 	-> seed( $_POST );

			$r 	-> save( !$save );
			if( !$save ) {
				_p_redirect( sprintf("/administration/relation/%d/%s" , $r -> get("id") , $r -> get("name") ));
			}
		}
		if( GPC::post_string("product-change") ) {
			if( GPC::post_int( "productid" )) {
				$p			= product::find_one_by_id( GPC::post_int( "relationid" ) );
				$save		= true;
			} else {
				$p			= new product;
				$save		= false;
			}
			$v			= array(
				"name" , "description" , "category" , "price" , "billperiod" , "billunits"
			);
			foreach( $v as $req ) {
				
				if( $req == "price" ) {
					$t		= GPC::post_string( $req );
					$t		= (float) $t;
					$t		= (int)($t * 100);
					$p -> set( $req , $t );
				} else {
					$p -> set( $req , GPC::post_string( $req ) );
				}
			}
			$p 	-> save( !$save );
		}
		if( GPC::post_string("relation-product") == "add" ) {
			$ps				= GPC::post_int( "product-select" , 0 );
			if( $ps > 0 ) {
				if($p = product::find_one_by_id( $ps )) {
					$rp				= new relation_product;
					$rp -> seed( $p );
					// remove product id from relationproduct object
					$rp -> set( "id" , NULL );
					
					$rp -> set( "added" , AnewtDatetime::now() );
					$rp -> set( "product" , $ps );
					$rp -> set( "relation" , GPC::post_int( "relationid" ) );
					$rp -> save();
				}
			
			}
			
		}
		if( GPC::post_string("invoice-change") ) {
			if( GPC::post_int( "invoiceid" ) ) {
				$i		= invoice::find_one_by_id( GPC::post_int( "invoiceid" ) );
				$i -> set( "updated" , AnewtDatetime::now() );
				$save		= true;
			} else {
				$i 		= new invoice;
				$i -> set( "state" , 101 );
				$i -> set( "created" , AnewtDatetime::now() );
				$save		= false;
			}
			$post			= $_POST;
			$i	-> seed( $post );
			$i 	-> save( !$save );
			if( !$save ) {
				_p_redirect( sprintf( "/administration/invoice/%d" , $i -> get("id") ));
			}
		}
	}
	public function display() {
		if( $this -> tpl ) {
			return $this -> parseTemplate( $this -> tpl , $this -> vars );
		}
	}
	private function overview() {
		
		$this -> vars['invoices']	= invoice::find_all();
		$this -> vars['relations']	= relation::find_all();
		
		global $m_adm_cache;
		$m_adm_cache['invoices']	= $this -> vars['invoices'];
		$m_adm_cache['relations']	= $this -> vars['relations'];
		
		$this -> tpl				= "overview";
	}
	// require SSL
	public function _SSL_() {
		return true;
	}
	// require LOGIN
	public function _LOGIN_() {
		return "system";
	}
	private function invoice() {
		if( $this -> pathid ) {
			$invoice				= invoice::find_one_by_id( $this -> pathid );
			if( $invoice ) {
				$this -> tpl		= "invoice.show";
				$this -> vars['invoice']
									= $invoice;
				$this -> vars['products']
									= product::find_all();
			} else 
			# relation not found
			{
				$this -> tpl		= "invoice.not.found";
			}
		} else {
			$this -> tpl			= "invoice.list";
		}
	}
	private function relation() {
		if( $this -> pathid ) {
			$relation				= relation::find_one_by_id( $this -> pathid );
			if( $relation ) {
				$this -> tpl		= "relation.show";
				$this -> vars['relation']
									= $relation;
			} else 
			# relation not found
			{
				$this -> tpl		= "relation.not.found";
			}
		} else {
			$this -> tpl			= "relation.list";
		}
	}
	private function product() {
		if( $this -> pathid ) {
			$product				= product::find_one_by_id( $this -> pathid );
			if( $product ) {
				$this -> tpl		= "product.show";
				$this -> vars['product']
									= $product;
			} else 
			# product not found
			{
				$this -> tpl		= "product.not.found";
			}
		} else {
			$this -> tpl			= "product.list";
		}
	}
	/**
	*		Generic ajax call
	*
	*/
	public function ajax() {
		$r							= $this -> route -> path;
		
	}
} 
