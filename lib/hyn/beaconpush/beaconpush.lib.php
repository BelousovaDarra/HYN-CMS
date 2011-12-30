<?PHP
if( !defined("HYN")) { exit; }

require_once "lib/classes/beaconpush.php";

class BeaconPusher extends BeaconPush {
	
	static public function init(  ) {
		self::$api_key		= MultiSite::setting( "apikey" , "beaconpush" ) -> get("value");
		self::$secret_key	= MultiSite::setting( "secretkey" , "beaconpush" ) -> get("value");
		DOM::set_js( "http://cdn.beaconpush.com/clients/client-1.js" );
		Twig::addVar( "beaconpush_apikey" , self::$api_key );
		DOM::set_js( __DIR__ . DS . "beaconpush.js.twig" );
	}

}

BeaconPusher::init();