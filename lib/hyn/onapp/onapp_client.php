<?PHP
if(!defined("HYN")) { exit; }

class OnApp_client {
	private static $c	= null;
	private function __construct() {
		if( 	MultiSite::setting( "server" , "onapp" )->get("value")
			&& MultiSite::setting( "username" , "onapp" ) -> get("value")
			&& MultiSite::setting( "apikey" , "onapp" ) -> get("value")
		) {
			$this -> host	= MultiSite::setting( "server" 		, "onapp" )->get("value");
			$this -> un	= MultiSite::setting( "username" 	, "onapp" )->get("value");
			$this -> pw	= MultiSite::setting( "apikey" 		, "onapp" )->get("value");
			return true;
		}
		throw new Exception( "OnApp Client cannot be initiated, missing domain settings" );
		return false;
	}
	static public function get_instance() {
		if( is_null( self::$c )) {
			self::$c	= new OnApp_client();
		}
		return self::$c;
	}
	static public function getUser( $id ) {
		$c = self::get_instance();
		$u = new OnApp_User;
		$u -> auth( $c -> host , $c -> un , $c -> pw );
		return $u -> load( $id );
	}
	static public function getProfile( $id ) {
		$c = self::get_instance();
		$u = new OnApp_Profile;
		$u -> auth( $c -> host , $c -> un , $c -> pw );
		return $u -> load( $id );
	}
	static public function getVMs( $id=NULL ) {
		$c = self::get_instance();
		$u = new OnApp_VirtualMachine;
		$u -> auth( $c -> host , $c -> un , $c -> pw );
		return $u -> getList( $id );
	}
	static public function getVMNetworkUsage( $id=false , $interface=false ) {
		if( $id && $interface ) {
			$c = self::get_instance();
			$u = new ONAPP_VirtualMachine_NetworkInterface_Usage;
			$u -> auth( $c -> host , $c -> un , $c -> pw );
			
			return $u -> getList( $id , $interface );
		}
		return false;
	}
}