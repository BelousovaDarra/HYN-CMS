<?PHP
if(!defined("HYN")) { exit; }

class SiteUser_ extends BasicUser {
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
			"signedup"		=> "datetime",
			"lastactivity"	=> "datetime",
			"state"			=> "integer"		// 1 - signed up, 0 - banned
		);
	}
}
BasicUser::register('SiteUser');