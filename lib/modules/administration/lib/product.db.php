<?PHP
if( !defined("HYN")) { exit; }

class product_ extends ModuleRecord {
	protected static function _db_table()
	{
		return "m_adm_product";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",		// user id
			"name"			=> "string",
			"description"	=> "string",
			"category"		=> "integer",
			"price"			=> "integer",
			"billperiod"	=> "string",		// day, week, months
			"billunits"		=> "integer",		// number of previous period
			"exvat"			=> "boolean",
		);
	}
}

ModuleRecord::register("product");