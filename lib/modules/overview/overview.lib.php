<?PHP
if( !defined("HYN")) { exit; }

class overview extends module {
	protected function _overview() {
		# check for domain
		if( GPC::post_string("hxs-domain-check")) {
			hyn_include( "hxsapi" );
			$hxs			= new hxsclient( HXS_API_UN , HXS_API_PW );
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
		DOM::add_js( 'jQuery("[data-twipsy-content]").twipsy({ live: true, html: true, title: \'data-twipsy-content\' });' , "body" );

		DOM::add_js( '	jQuery("input[name=\'free-trial\']").click(function() {
					if( jQuery(this).attr("checked") ) {
						jQuery("input[name=\'payment\'][value=0]:disabled").attr("disabled",false);
						jQuery("input[name=\'order-domain\'][value=7]:disabled").attr("disabled",false);
					} else {
						jQuery("input[name=\'order-domain\'][value=7]").attr("disabled",true);
						jQuery("input[name=\'payment\'][value=0]").attr("disabled",true);
						jQuery("input[name=\'order-domain\'][value!=7]").first().attr("checked",true);
						jQuery("input[name=\'payment\'][value!=0]").first().attr("checked",true);
					} 
				});
		' , "body" );
#		DOM::add_js( 'jQuery("input[name=\'free-trial\']").click(function() {jQuery("input[name=\'payment\'][value=0]").attr("disabled",false);});' , "body" );
	}
	public function display() {
		# start sparking
		if( $this -> route -> path[0] == "spark" ) {
			// cloud spark form filled in and submitted
			if( GPC::post_string( "spark" ) ) {
				$fields		= $_POST;
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
