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
			"id"			=> "integer",
			"name"			=> "string",
			"description"	=> "string",
			"category"		=> "integer",
			"billduration"	=> "string",
			"price"			=> "integer",
			"billperiod"	=> "string",		// day, week, months
			"billunits"		=> "integer",		// number of previous period
			"exvat"			=> "boolean",		// if exvat, price is counted as such
		);
	}
	public function get_price_exvat_($vat=19) {
		if( $this -> _get( "price" ) == 0 ) {
			return 0;
		}
		if( $this -> _get( "exvat" )) {
			return $this -> _get("price");
		} else {
			return ( $this -> _get("price") / 100 ) * $vat;
		}
	}
	public function get_categoryname_() {
		global $m_adm_cache;
		if( isset($m_adm_cache['vars']) && isset($m_adm_cache['vars']['categories']) && isset($m_adm_cache['vars']['categories'][ $this -> _get('category') ])) {
			return $m_adm_cache['vars']['categories'][ $this -> _get('category') ] -> get("name");
		} else {
			return;
		}
	}
	public function get_billing_() {
		global $m_adm_cache;
		return sprintf( "%s%s" , ($this -> _get("billunits") > 1 ? $this -> _get("billunits") . " " : false ) , $m_adm_cache['vars']['billperiods'][$this -> _get("billperiod")][( $this -> _get("billunits") > 1 ? 1 : 0 )] );
	}
}

ModuleRecord::register("product");