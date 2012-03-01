<?PHP
if( !defined("HYN")) { exit; }
 
class invoice_ extends ModuleRecord {
	protected static function _db_table()
	{
		return "m_adm_invoice";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",		// id
			"relation"		=> "integer",
			"created"		=> "datetime",
			"updated"		=> "datetime",
			"state"			=> "integer",
		);
	}
}

ModuleRecord::register("invoice");