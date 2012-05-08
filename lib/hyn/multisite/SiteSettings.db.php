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
	protected static function _db_primary_keys() {
		return array( "name" , "module" );
	}
	static public function find_by_name_module( $name , $module=false ) {
		return SiteSettings::find_by_sql( 
			sprintf("WHERE `name` = '%s'". ($module ? " AND `module` = '%s'" : false ) , $name , ($module ? $module : NULL ))
		);
	}
	static public function find_one_by_name_module( $name , $module=false ) {
		return SiteSettings::find_one_by_sql( 
			sprintf("WHERE `name` = '%s'". ($module ? " AND `module` = '%s'" : false ) , $name , ($module ? $module : NULL ))
		);
	}
}

AutoRecord::register('SiteSettings');

function SiteSetting( $name=false , $module=false ) {
	$ss	= SiteSettings::find_one_by_name_module( $name , $module ); 
	if( $ss && count($ss) == 1 ) { return $ss -> get("value"); }
	return false;
}
function SaveSiteSetting( $name , $module , $value ) {
	$ss	= SiteSettings::find_one_by_name_module( $name , $module ); 
	if( !$ss || count($ss) < 1 ) {
		$ss 		= new SiteSetting;
		$ss -> set( "module" 	, $module );
		$ss -> set( "name" 		, $name );
	}
	$ss -> set( "value" , $value );
	$ss -> save();
	return $ss;
}