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
			"route"			=> "string",
			"title"			=> "string",
			"subtitle"		=> "string",
			"content"		=> "string",
			"created"		=> "datetime",
			"updated"		=> "datetime",
			
		);
	}
}

ModuleRecord::register("pages");