<?PHP

if( !defined("HYN")) { exit; }

class modules_ extends AutoRecord {
	protected static function _db()
	{
		return AnewtDatabase::get_connection( "hyn" );
	}
	protected static function _db_table()
	{
		return "system_modules";
	}
	protected static function _db_columns()
	{
		return array(
			"name"						=> "string",
			"systemname"				=> "string",
			"folder"					=> "string",
			"summary"					=> "string",
			"description"				=> "string",
			"version"					=> "integer",
			"require_install"			=> "boolean",
			"author"					=> "string",		// user ID: <site>.<userid>
			"fee_once"					=> "integer",
			"fee_recurring_cost"		=> "integer",
			"fee_recurring_period"		=> "string",		// m or y
			"fee_to_adm_relation"		=> "integer",		// connected to HYN's own administration (prime site)
			"dependency"				=> "string",		// needs other module or modules
			"routable"					=> "boolean",		// can be set for routing purposes
			"core"						=> "boolean",		// cannot be installed
			"local"						=> "boolean",		// not visible on all sites - only visible on domain it is installed in (local module in domains dir)
		);
	}
	protected static function _db_primary_keys() {
		return array( "systemname" , "version" );
	}
	public function get_cp_() {
		$class			= $this -> get("systemname");
		// require the cp lib file for this module
		if( is_file( sprintf( "%s%s%smain.lib.php" , HYN_MS_DIR_MODULES , $class , DS ))) {
			require_once sprintf( "%s%s%smain.lib.php" , HYN_MS_DIR_MODULES , $class , DS );
		}
		elseif( is_file( sprintf( "%s%s%smain.lib.php" , HYN_PATH_MODULES , $class , DS ))) {
			require_once sprintf( "%s%s%smain.lib.php" , HYN_PATH_MODULES , $class , DS );
		}
		if( is_file( sprintf( "%s%s%s%s.cp.php" , HYN_MS_DIR_MODULES , $class , DS , $class ))) {
			require_once sprintf( "%s%s%s%s.cp.php" , HYN_MS_DIR_MODULES , $class , DS , $class );
		} elseif( is_file(sprintf( "%s%s%s%s.cp.php" , HYN_PATH_MODULES , $class , DS , $class ))) {
			require_once sprintf( "%s%s%s%s.cp.php" , HYN_PATH_MODULES , $class , DS , $class );
		} else { return false; }
		// instantiate a class for later calling
		$c								= sprintf( "%sCP" , $class );
		return new $c;
	}
}

ModuleRecord::register( "modules" );