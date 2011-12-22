<?PHP
if( !defined("HYN") ) { exit; }

class MultiSiteUser_ extends AutoRecord {

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
			"id"			=> "integer",
			"email"			=> "string",
			"password"		=> "string",
			"realname"		=> "string",
			"dob"			=> "date",
			"status"		=> "integer",
			"signedup"		=> "datetime",
			"lastactivity"		=> "datetime",
			"country"		=> "string",
			"admin"			=> "integer",
			"uid"			=> "string"
		);
	}
	public function get_isSiteOwner() {
		global $MultiSite;
		if( $MultiSite -> owner && $MultiSite -> owner -> get("id") == $this -> get("id") ) {
			return true;
		}
		return false;
	}
}

AutoRecord::register('MultiSiteUser');