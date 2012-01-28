<?PHP
if( !defined("HYN")) { exit; }

function curl_json( $url ) {
	return json_decode( curl_get( $url ) );
}
function curl_get( $url ) {
	if( !_v($url) ) {
		return false;
	}
	$c = curl_init( $url );
	curl_setopt( $c , CURLOPT_RETURNTRANSFER , true );
	return curl_exec( $c );
}