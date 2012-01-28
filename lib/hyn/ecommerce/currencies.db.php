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
			"toeuro"		=> "integer",
			"updated"		=> "datetime",
		);
	}
}

AutoRecord::register("currencies");