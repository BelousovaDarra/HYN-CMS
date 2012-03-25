<?PHP
if( !defined("HYN")) { exit; }

class invoice_state_ extends ModuleRecord {
	protected static function _db_table()
	{
		return "m_adm_invoice_states";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",
			"state"			=> "integer",
			"short"			=> "string",
			"long"			=> "string",
			"options"		=> "string",
		);
	}
}
ModuleRecord::register("invoice_state");