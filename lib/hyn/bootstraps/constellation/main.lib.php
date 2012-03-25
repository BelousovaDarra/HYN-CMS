<?PHP

if(!defined("HYN")) { exit; }

$path	= HYN_PATH_TPL . "constellation" . DS;

if( _v($path."css","dir")) {
	$css = scandir($path."css");
	foreach( $css as $cssfile ) {
		DOM::set_css( $cssfile );
	}
}