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

function SiteSetting( $name=false , $module=false ) {
		$ss	= SiteSettings::find_one_by_sql( 
			sprintf("WHERE `name` = '%s'". ($module ? " AND `module` = '%s'" : false ) , $setting , ($module ? $module : NULL ))
		);
		if( $ss ) { return $ss -> get("value"); }
		return false;
}