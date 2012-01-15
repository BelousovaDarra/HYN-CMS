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
		DOM::set_css( HYN_PATH_HYN . "bootstrap".DS."bootstrap.css" );
		DOM::set_css( HYN_PATH_TPL . "style.css" );
		
		DOM::set_js( "https://www.google.com/jsapi" );
		DOM::set_js( HYN_URI_HTTPS . HYN_URI_HTTPHOST . "/js/jq+jqui.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-twipsy.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-popover.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-dropdown.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-buttons.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-alerts.js" );
		
/*		hyn_include( "beaconpush" );
#		BeaconPusher::send_to_channel( "notify" , "test" , array("Yes we are testing the push") );
*/	
		DOM::set_meta( "name" , "generator" , "HYN.me" );
		
		DOM::set_js( HYN_PATH_PUBLIC_JS . "jq-slides.js" );
		DOM::set_css( filefind( "slides.css" , $this -> class ) );
		DOM::set_js( filefind( "slides-load.js" , $this -> class ) );
		DOM::add_js( 'jQuery("a[rel=popover]").popover({ live: true });' , "body" );
	}
	public function display() {
		# start sparking
		if( $this -> route -> path[0] == "spark" ) {
			
			if( GPC::post_string( "spark" ) ) {
				
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
