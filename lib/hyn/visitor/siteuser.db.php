<?PHP
if(!defined("HYN")) { exit; }

class SiteUser_ extends AutoRecord {
	protected static function _db()
	{
		return AnewtDatabase::get_connection( "default" );
	}
	protected static function _db_table()
	{
		return "site_user";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",		// user id
			"domain"		=> "integer",		// domain id
			"email"			=> "string",		// email address
			"password"		=> "string",		// password
			"realname"		=> "string",		// realname
			"created"		=> "datetime",
			"updated"		=> "datetime",
			"state"			=> "integer"		// 1 - signed up, 0 - banned
		);
	}
	static function find_one_by_un( $un ) {
		return self::find_one_by_column( "email" , $un );
	}
	public function get_banned_() {
		if( $this -> get("state") > 0 ) { # not banned
			return false;
		} else { return true; } # yes banned
	}
}
AutoRecord::register('SiteUser');