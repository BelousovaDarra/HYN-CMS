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
		
		hyn_include( "beaconpush" );
	
		DOM::set_meta( "name" , "generator" , "HYN.me" );		
	}
	public function display() {
		if( isset( $this -> domain )) {
			return $this -> parseTemplate( "domaincheck" , array( "domain" => $this -> domain[0] ) );
		} else {
			return $this -> parseTemplate( "overview" );
		}
	}
} 
