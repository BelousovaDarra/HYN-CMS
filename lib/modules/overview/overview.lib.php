<?PHP
if( !defined("HYN")) { exit; }

class overview extends module {
	public function display() {
		DOM::set_css( HYN_PATH_HYN . "bootstrap".DS."bootstrap.min.css" );
		DOM::set_css( HYN_PATH_TPL . "style.css" );
		
		DOM::set_js( "https://www.google.com/jsapi" );
		DOM::set_js( HYN_PATH_TPL . "load.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-twipsy.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-popover.js" );
		DOM::set_js( HYN_PATH_HYN . "bootstrap".DS."js".DS."bootstrap-dropdown.js" );
		DOM::set_meta( "name" , "generator" , "HYN.me" );
		return $this -> parseTemplate( "overview.twig" );
	}
} 
