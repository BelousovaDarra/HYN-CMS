<?PHP
if( !defined("HYN")) { exit; }

class productcategory_ extends ModuleRecord {
	protected static function _db_table()
	{
		return "m_adm_product_category";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",		// user id
			"name"			=> "string",
			"description"	=> "string",
			"subof"			=> "integer",
		);
	}	
}

ModuleRecord::register("productcategory");