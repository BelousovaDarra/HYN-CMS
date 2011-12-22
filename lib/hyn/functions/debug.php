<?PHP
if(!defined("HYN")) { exit; }
// debug function
function _debug( $msg="debug testing" , $end=false ) {
	if( !HYN_DEBUG ) { return false; }
	echo "<pre>";
	var_dump($msg);
	if( is_object( $msg )) {
		debug_print_backtrace(  );
	} 
	echo "</pre>";
	if( $end )  exit;
}