<?PHP

if( !defined("HYN")) { exit; }

class Currency {
	
	static $i			= NULL;
	
	private function __construct() {
		
	}
	static public function get_instance() {
		if( is_null( self::$i )) {
			self::$i		= new Currency;
		}
		return self::$i;
	}
	static public function setCurrency( $c="EUR" ) {
		$i				= self::get_instance();
		$i -> currency	= $c;
	}
	static public function updateSupportedCurrencies() {
		$curs			= currencies::find_all();
		if( !count( $curs )) { return; }
		if( _v($curs,"array")) {
			foreach( $curs as $c ) {
				$rate	= curl_get( "http://www.google.com/ig/calculator?hl=en&q=1000".$c -> get("iso")."%3D%3FEUR" );
				$regex	= "/rhs: \"([0-9\.]+)/i";
				preg_match( $regex , $rate , $m );
				$rate	= (int) $m[1];
				$c -> set( "toeuro" , $rate );
				$c -> set( "updated" , AnewtDatetime::now() );
				$c -> save();
				_debug( $c );
				_debug( $rate );
			}
		} else { return; }
	}
}