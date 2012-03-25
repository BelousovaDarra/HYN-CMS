<?PHP
if( !defined("HYN") ) { exit; }

class currencies_ extends AutoRecord {

	protected static function _db()
	{
		return AnewtDatabase::get_connection( "hyn" );
	}
	protected static function _db_table()
	{
		return "system_currencies";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",
			"iso"			=> "string",
			"name"			=> "string",
			"1USD"			=> "float",
			"1EUR"			=> "float",
			"updated"		=> "datetime",
		);
	}
	/**
	*	calculate any amount from euro to the loaded currency
	*	@return amount in integer and different currency
	*/
	public function fromEUR( $amount ) {
		if( is_float( $amount )) {
			$amount = round( $amount * 100 );
		}
		if( !is_int( $amount ) || !$amount ) {
			return false;
		}
		return round( $amount * $this -> get("1EUR") );
	}
	public static function find_one_by_iso( $iso="EUR" ) {
		return currencies::find_one_by_column( "iso" , $iso );
	}
}

AutoRecord::register("currencies");