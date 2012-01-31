<?PHP
if( !defined("HYN")) { exit; }

class pages_ extends ModuleRecord {
	protected static function _db_table() {
		return "m_pages";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "int",
			"name"			=> "string",
			"text"			=> "string",
			"added"			=> "datetime",
			"updated"		=> "datetime",
			
		);
	}	
}

ModuleRecord::register("pages");