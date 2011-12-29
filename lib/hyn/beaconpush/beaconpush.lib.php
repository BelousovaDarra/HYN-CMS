<?PHP
if( !defined("HYN")) { exit; }

# beaconpush test
if( $s = MultiSite::setting( "apikey" , "beaconpush" )) {
	DOM::set_js( "http://cdn.beaconpush.com/clients/client-1.js" );
	Twig::addVar( "beaconpush_apikey" , $s -> get("value") );
	DOM::set_js( __DIR__ . DS . "beaconpush.js.twig" );

}