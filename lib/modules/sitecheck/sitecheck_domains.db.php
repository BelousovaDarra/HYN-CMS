<?PHP

class websitecheck_ extends ModuleRecord {
	protected static function _db_table() {
		return "site_sitecheck_sites";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "int",
			"domain"		=> "string",		// domain id
			"json"			=> "string",		// email address
			"created"		=> "datetime",
			"updated"		=> "datetime",
		);
	}
} 

ModuleRecord::register("websitecheck");