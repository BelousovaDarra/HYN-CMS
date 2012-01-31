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
		
		$curs			= curl_json( "http://openexchangerates.org/latest.json" );
		$names			= curl_json( "http://openexchangerates.org/currencies.json" );
		$base			= $curs -> base;
		$base2eur		= 1 / $curs -> rates -> EUR;

		foreach( $curs -> rates as $valuta => $rate ) {
			$new 	= NULL;
			if( !$c = currencies::find_one_by_column( "iso" , $valuta )) {
				$c = new currencies; 
				$c -> set( "iso" , $valuta );
				$c -> set( "name" , $names -> $valuta );
				$new = true;
			}
			$c -> set( "updated" , AnewtDatetime::now() );
			$c -> set( "1USD" , $rate );
			$c -> set( "1EUR" , round(( 1 / $base2eur ) * $rate ,6) );
			$c -> save($new);
		}

		
		/*
		$curs			= currencies::find_all();
		if( !count( $curs )) { return; }
		if( _v($curs,"array")) {
			foreach( $curs as $c ) {
				$rate		= curl_get( "http://www.google.com/ig/calculator?hl=en&q=1000".$c -> get("iso")."%3D%3FEUR" );
				$regex		= "/rhs: \"([^a-z]+)/i";
				preg_match( $regex , $rate , $m );
				_debug( $m[1] );
				$rate		= (float) $m[1];
				$rate		= round( $rate );

				$rate		= (int) $rate;
				$c -> set( "toeuro" , $rate );
				$c -> set( "updated" , AnewtDatetime::now() );
				$c -> save();
				_debug( $rate );
			}
		} else { return; }*/
	}
}