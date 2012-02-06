<?PHP
if( !defined("HYN")) { exit; }

class overview extends module {
	protected function _overview() {
		# check for domain
		if( GPC::post_string("hxs-domain-check")) {
			hyn_include( "hxsapi" );
			$hxs			= new hxsclient( MultiSite::setting( "resellerid" , "hxsclient" ) -> get("value") , MultiSite::setting( "resellerpw" , "hxsclient" ) -> get("value") );
			$this -> domain	= $hxs -> checkDomain( GPC::post_string("hxs-domain-name" ));
		}

		hyn_include( "bootstraps/twitter" );
		
		DOM::set_css( "style.css" );
		
/*		hyn_include( "beaconpush" );
#		BeaconPusher::send_to_channel( "notify" , "test" , array("Yes we are testing the push") );
*/	
		DOM::set_meta( "name" , "generator" , "HYN.me" );

		DOM::add_js( 'jQuery("[data-popover-placement=\'right\']").popover({ live: true, html: true, title: \'data-popover-title\', content: \'data-popover-content\' });' , "body" );
		DOM::add_js( 'jQuery("[data-popover-placement=\'left\']").popover({ live: true, html: true, title: \'data-popover-title\', content: \'data-popover-content\', placement: \'left\' });' , "body" );
		DOM::add_js( 'jQuery("[data-tooltip-content]").tooltip({ live: true, html: true, title: \'data-tooltip-content\' });' , "body" );

		DOM::add_js( '	jQuery("input[name=\'free-trial\']").click(function() {
					if( jQuery(this).attr("checked") ) {
						jQuery("input[name=\'payment\'][value=0]:disabled").attr("disabled",false);
						jQuery("input[name=\'order-domain\'][value=5]:disabled").attr("disabled",false);
					} else {
						jQuery("input[name=\'order-domain\'][value=5]").attr("disabled",true);
						jQuery("input[name=\'payment\'][value=0]").attr("disabled",true);
						jQuery("input[name=\'order-domain\'][value!=5]").first().attr("checked",true);
						jQuery("input[name=\'payment\'][value!=0]").first().attr("checked",true);
					} 
				});
		' , "body" );		
#		DOM::add_js( 'jQuery("input[name=\'free-trial\']").click(function() {jQuery("input[name=\'payment\'][value=0]").attr("disabled",false);});' , "body" );
	}
	/**
	*		information pages ; uses page class?
	*/
	public function discover() {
		if( !class_exists("page")) {
			# [TODO] show error
		}
		// load page
		$p 		= new page;
		return $p -> display();
	}
	/**
	*		shows all options and store it into session and server to be used if order is not finished
	*/
	public function decide() {
		
	}
	/**
	*		deploy shows registration steps and sets up everything for (almost) instant use
	*/
	public function deploy() {
		
	}
	/**
	*		shows default frontpage
	*/
	public function display() {
		# start sparking
		if( $this -> route -> path[0] == "spark" ) {
			// cloud spark form filled in and submitted
			if( GPC::post_string( "spark" ) ) {
				$fields		= $_POST;
				
				
				
				return $this -> parseTemplate( "review" , array( "fields" => $fields ) );
			}
			return $this -> parseTemplate( "startcloud" , array( "domain" => $this -> route -> path[1] ) );
		} else
		# domain check
		if( isset( $this -> domain )) {
			return $this -> parseTemplate( "domaincheck" , array( "domain" => $this -> domain[0] ) );
		} else {
			return $this -> parseTemplate( "overview" );
		}
	}
} 
