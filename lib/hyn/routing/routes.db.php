<?PHP
if( !defined("HYN")) { exit; }

class routes_ extends AutoRecord {
	protected static function _db()
	{
		return AnewtDatabase::get_connection( "default" );
	}
	protected static function _db_table()
	{
		return "site_routes";
	}
	protected static function _db_columns()
	{
		return array(
			"route"			=> "string",		// route path
			"module"		=> "string",		// module path
			"function"		=> "string",		// function
			"active"		=> "boolean",		// active
		);
	}
	static public function routefrompath($path) {
		return routes::find_one_by_sql( "WHERE `route` = ?string? AND `active`" , $path );
	}
}
AutoRecord::register('routes');