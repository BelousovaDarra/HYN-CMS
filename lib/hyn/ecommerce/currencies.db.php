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
}

AutoRecord::register("currencies");