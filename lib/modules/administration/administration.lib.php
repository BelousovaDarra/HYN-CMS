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
		DOM::set_js( "https://maps.googleapis.com/maps/api/js?key=AIzaSyA7YULvmwWO9lgoaky93cACLYN-vZac9ps&sensor=true" );
		DOM::add_js( $this -> relation_map() );
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
				$this -> relation();
				break;
			case "product":
				$this -> product();
				break;
			default:
				$this -> overview();
		}

	}
	private function relation_map() {
		return "
//		function maps_init() {
			if( jQuery('.relation-location-map').length > 0	) {
				var map			= [];
				jQuery( '.relation-location-map' ).each( function( i ) {
					var loc		= jQuery( this ).attr( 'data-location');
					var opts	= {
						zoom:			8,
						mapTypeId: 		google.maps.MapTypeId.ROADMAP,
						center: 		codeAddress( loc )
					};
					map.push( new google.maps.Map( jQuery(this) , opts ) );
				});
			}
			function codeAddress( address ) {
				var geocoder	= new google.maps.Geocoder();
				geocoder.geocode( { 'address': address}, function(results, status) {
					return results[0].geometry.location;
			  	});
			}
//		}
		";
	}
	private function processGPCs() {
		# create relation
		if( GPC::post_string("relation")) {
			if( GPC::post_int( "relationid" )) {
				$r			= relation::find_one_by_id( GPC::post_int( "relationid" ) );
				$save		= false;
			} else {
				$r			= new relation;
				$save		= true;
			}
			
			$post			= $_POST;
			$post['vat']	= (int) ($post['vat'] * 100);
			
			$r -> seed( $_POST );

			$rid		= $r -> save( $save );
			if( $save ) {
				_p_redirect( "/administration/relation/". $rid );
			}
		}
		if( GPC::post_string("product") ) {
			if( GPC::post_int( "productid" )) {
				$p			= product::find_one_by_id( GPC::post_int( "relationid" ) );
				$save		= false;
			} else {
				$p			= new product;
				$save		= true;
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
			$pid		= $p -> save( $save );
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
		$this -> tpl				= "overview";
	}
	// require SSL
	public function _SSL_() {
		return true;
	}
	// require LOGIN
	public function _LOGIN_() {
		return true;
	}
	private function invoice() {
		if( $this -> pathid ) {
			$invoice				= invoice::find_one_by_id( $this -> pathid );
			if( $invoice ) {
				$this -> tpl		= "invoice.show";
				$this -> vars['invoice']
									= $invoice;
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
	public function ajax() {
		$r							= $this -> route -> path;
		
	}
} 
