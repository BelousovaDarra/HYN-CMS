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
	protected static function _db_primary_key() {
		return "route";
	}
	static public function routefrompath($path="/") {
		# string
		if( !_v($path,"array")) {
			return routes::find_one_by_sql( "WHERE `route` = ?string? AND `active`" , $path );
		}
		$i					= count( $path );
		$check				= $path;
		while( $i >= 0 ) {
			$checkroute		= implode( "/" , $check );
			if( $r = routes::find_one_by_sql( "WHERE `route` = ?string? AND `active`" , "/".$checkroute ) ) {
				return $r;
			}
			array_pop( $check );
			$i--;
		}
		if( $r	= routes::find_one_by_sql( "WHERE `route` = ?string? AND `active`" , $path."%" )) {
			return $r;
		}
	}
	static public function find_route_by_module_function( $module , $function ) {
		return routes::find_one_by_sql( "WHERE `function` = ?string? AND `module` = ?string? AND `active`" , $function , $module );
	}
}
AutoRecord::register('routes');