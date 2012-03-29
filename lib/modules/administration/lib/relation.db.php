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
			"billperiod"	=> "string",		// day, week, months, years
			"billunits"		=> "integer",		// number of previous period
			"currency"		=> "string",
			"vat"			=> "integer"		// tax percentage
		);
	}
	protected static function _db_sort_column() {
		return "name";
	}
	protected static function _db_sort_order() {
		return "DESC";
	}
	public function get_companytype_() {
		global $m_adm_cache;
		if( isset($m_adm_cache['vars']) && isset($m_adm_cache['vars']['orgtypes']) && isset($m_adm_cache['vars']['orgtypes'][ $this -> _get('company') ])) {
			return $m_adm_cache['vars']['orgtypes'][ $this -> _get('company') ];
		} else {
			return;
		}
	}
	public function get_ahref_() {
		return "/administration/relation/" . $this -> id . "/" . urlencode( $this -> name );
	}
}
ModuleRecord::register("relation");