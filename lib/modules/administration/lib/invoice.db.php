<?PHP
if( !defined("HYN")) { exit; }
 
class invoice_ extends ModuleRecord {
	protected static function _db_table()
	{
		return "m_adm_invoice";
	}
	protected static function _db_columns()
	{
/*		return array(
			"id"			=> "integer",		// user id
			"domain"		=> "integer",		// domain id
			"email"			=> "string",		// email address
			"password"		=> "string",		// password
			"realname"		=> "string",		// realname
			"created"		=> "datetime",
			"updated"		=> "datetime",
			"state"			=> "integer"		// 1 - signed up, 0 - banned
		);*/
	}
}