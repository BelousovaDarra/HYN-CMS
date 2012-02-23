<?PHP


class Country_ extends AutoRecord {
	protected static function _db()
	{
		return AnewtDatabase::get_connection( "hyn" );
	}
	protected static function _db_table()
	{
		return "system_country";
	}
	protected static function _db_columns()
	{
		return array(
			"id"				=> "integer",
			"iso"				=> "string",		// email address
			"name"				=> "string",		// password
			"printable_name"	=> "string",		// realname
			"iso3"				=> "string",
			"numcode"			=> "integer",
		);
	}
}
AutoRecord::register("Country");