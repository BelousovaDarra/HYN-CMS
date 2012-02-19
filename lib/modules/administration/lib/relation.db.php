<?PHP
if( !defined("HYN")) { exit; }

class relation_ extends ModuleRecord {
	protected static function _db_table()
	{
		return "m_adm_relation";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",		// user id
			"uid"			=> "string",		// optional; relates to a user
			"name"			=> "string",
			"company"		=> "string",		// optional
			"taxno"			=> "string",		// optional
			"cocno"			=> "string",		// optional
			"address"		=> "string",
			"houseno"		=> "string",
			"postal"		=> "string",
			"city"			=> "string",
			"country"		=> "string",
			"phone"			=> "string",
			"email"			=> "string",
			"billperiod"	=> "string",		// day, week, months
			"billunits"		=> "integer",		// number of previous period
			"currency"		=> "string",
		);
	}
}
ModuleRecord::register("relation");