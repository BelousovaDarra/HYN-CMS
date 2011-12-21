<?PHP
if( !defined("HYN") ) { exit; }

class MultiSiteAlias_ extends AutoRecord {

	protected static function _db()
	{
		return AnewtDatabase::get_connection( "hyn" );
	}
	protected static function _db_table()
	{
		return "system_domain_alias";
	}
	protected static function _db_columns()
	{
		return array(
			"aliasid"		=> "integer",
			"alias"			=> "string",
			"aliasof"		=> "integer",
		);
	}
	protected static function _db_primary_key() {
		return "aliasid";
	}
}

AutoRecord::register('MultiSiteAlias');