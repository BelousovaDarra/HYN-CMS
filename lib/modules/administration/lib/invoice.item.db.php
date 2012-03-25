<?PHP
if( !defined("HYN")) { exit; }

class invoice_item_ extends ModuleRecord {
	protected static function _db_table()
	{
		return "m_adm_invoice_item";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",		// user id
			"invoice"		=> "integer",		// @rel: invoice.id
			"product"		=> "integer",		// @rel: product.id
			"description"	=> "string",
		);
	}
	protected static function _db_join_many() {
		return array(
				array(
					"foreign_class"		=> "product",
					"foreign_key"		=> "id",
					"foreign_alias"		=> "product",
					"own_key"			=> "product",
					"multi"				=> false,
					"child_name"		=> "product"
				)
		);
	}
	public function get_item_product_() {
		global $m_adm_cache;
		if( is_int( $this -> get("product")) && isset($m_adm_cache['product'][ $this -> get("product") ]) ) {
			return $m_adm_cache['product'][ $this -> get("product") ];
		} elseif( is_int( $this -> get("product"))) {
			$m_adm_cache['product'][ $this -> get("product") ]	= product::find_one_by_id( $this -> get("product") );
			return $m_adm_cache['product'][ $this -> get("product") ];
		} else {
			return $this -> get("product");
		}
	}
}

ModuleRecord::register("invoice_item");