<?PHP
if( !defined("HYN")) { exit; }

class SiteSettings_ extends AutoRecord {

	protected static function _db()
	{
		return AnewtDatabase::get_connection( "default" );
	}
	protected static function _db_table()
	{
		return "site_settings";
	}
	protected static function _db_columns()
	{
		return array(
			"name"			=> "string",
			"module"		=> "string",
			"value"			=> "string",
		);
	}
	protected static function _db_primary_key() {
		return "name";
	}
}

AutoRecord::register('SiteSettings');