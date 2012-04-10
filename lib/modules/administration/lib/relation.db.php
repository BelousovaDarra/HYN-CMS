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
			"id"			=> "integer",		// relation id
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
			"billperiod"		=> "string",		// day, week, months, years
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
	public function get_last_invoices_( $num=5 ) {
		return invoice::find_by_sql( sprintf( "WHERE relation = %d LIMIT %d" , $this -> id , $num ) );
	}
	public function get_ahref_() {
		return "/administration/relation/" . $this -> id . "/" . urlencode( $this -> name );
	}
	public function get_href_() {
		return $this -> get("ahref");
	}
	public function get_products_active_() {
		return relation_product::find_active_by_relation( $this -> id );
	}
	public function get_gmaps_address_() {
		if( !$this -> get('houseno') 
			|| !$this -> get('address')
			|| !$this -> get('postal')
			|| !$this -> get('city')
			|| !$this -> get('country') ) {
			return false;
		} else {
			return sprintf( "%s %s, %s %s, %s"
			, $this -> get('houseno')
			, $this -> get('address')
			, $this -> get('postal')
			, $this -> get('city')
			, $this -> get('country')
			);
		}
	}
	public function get_vat_percentage_() {
		return $this -> get("vat") / 100;
	}
}
ModuleRecord::register("relation");