<?PHP
if( !defined("HYN")) { exit; }

class overview extends module {
	protected function _overview() {
		# check for domain
		if( GPC::post_string("hxs-domain-check")) {
			hyn_include( "hxsapi" );
			$hxs		= new hxsclient( HXS_API_UN , HXS_API_PW );
			_debug( $hxs -> checkDomain( GPC::post_string("hxs-domain-name" )) );
		}
		DOM::set_css( HYN_PATH_HYN . "bootstrap".DS."bootstrap.css" );
		DOM::set_css( HYN_PATH_TPL . "style.css" );
		
		DOM::set_js( "https://www.google.com/jsapi" );
		DOM::set_js( HYN_PATH_TPL . "load.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-twipsy.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-popover.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-dropdown.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-buttons.js" );
		DOM::set_meta( "name" , "generator" , "HYN.me" );		
	}
	public function display() {
		return $this -> parseTemplate( "overview.twig" );
	}
} 
