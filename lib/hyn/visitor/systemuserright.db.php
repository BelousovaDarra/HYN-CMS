<?PHP
if(!defined("HYN")) { exit; }

class SystemUserRight_ extends AutoRecord {
	protected static function _db()
	{
		return AnewtDatabase::get_connection( "hyn" );
	}
	protected static function _db_table()
	{
		return "system_user_rights";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",
			"uid"			=> "string",
			"right"			=> "string",
			"domain"		=> "string",
			"updated"		=> "datetime",
		);
	}
	public function find_one_by_uid_site( $uid , $site ) {
	
	}
}
AutoRecord::register('SystemUserRight');