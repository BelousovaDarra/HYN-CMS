<?PHP
if(!defined("HYN")) { exit; }

class SystemUser_ extends BasicUser {
	protected static function _db()
	{
		return AnewtDatabase::get_connection( "hyn" );
	}
	protected static function _db_table()
	{
		return "system_user";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",		// user id
			"email"			=> "string",		// email address
			"password"		=> "string",		// password
			"realname"		=> "string",		// realname
			"signedup"		=> "datetime",
			"lastactivity"	=> "datetime",
			"admin"			=> "integer",
			"state"			=> "integer",		// 1 - signed up, 0 - banned
		);
	}

}
AutoRecord::register('SystemUser');