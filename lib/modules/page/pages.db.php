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
	public function get_created_dmy() {
		return AnewtDatetime::format( "%d %h %Y" , $this -> get("created") );
	}
	public function get_updated_dmy() {
		return AnewtDatetime::format( "%d %h %Y" , $this -> get("updated") );
	}
}

ModuleRecord::register("pages");