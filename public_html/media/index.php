<?PHP

define( "HYN" , "cmd" );
/**
*
*/

$cachelifetime		= 3600 * 24 * 1;
require_once "../../lib/hyn/init.php"; 
error_reporting(0);
if( isset($_GET['f']) && $f = $_GET['f'] ) {
	# first check if the get is actually available in this public images directory (fall back)
	if( is_file( $f )) {
		showMedia( $f );
	}
	if( !defined("HYN_MS_DIR_MEDIA")) { exit; }
	# fallback where domain name is still in images uri
	if( strstr( $f , $MultiSite -> domain)) {
		$f		= str_replace( $MultiSite -> domain , "" , $f );
		$f		= str_replace( "//" , "/" , $f );
	}
	# check in multisite domain images directory
	
	if( mediaFind( $f )) {
		$file		= mediaFind( $f );
	}
	if(!isset($file) || !is_readable($file)) {
		exit;
	}
	showMedia($file);
} else {
# not found

}
function mediaFind( $f ) {
	if( is_file( HYN_MS_DIR_MEDIA . $f )) {
		return HYN_MS_DIR_MEDIA . $f ;
	}
	return false;
}
function showMedia($file) {
	global $cachelifetime;
	$finfo		= finfo_open( FILEINFO_MIME_TYPE );
	$info['mime']	= finfo_file( $finfo , $file );
	switch( $info['mime'] ) {
		case "text/x-c":
			if( preg_match( "/.css$/i",$file)) {
				$info['mime']	= "text/css";
			} else {
				$info['mime']	= "text/html";
			} 
		break;
	}
	finfo_close( $finfo );
	$f		= fopen($file,"rb");
	// pragma is a header setting which disallows caching; bad bad pragma!
	header_remove( "pragma" );

	header("Content-type: ".$info['mime']);
	header("Content-length: ".filesize($file));
	header("Cache-Control: max-age=".$cachelifetime);
	header("Last-Modified: ".gmdate("D, d M Y H:i:s" , filemtime($file)));
	header("Expires: ".gmdate("D, d M Y H:i:s" , (time() + $cachelifetime)));
	header('Content-Disposition: inline');

	fpassthru($f);
	exit;	
}