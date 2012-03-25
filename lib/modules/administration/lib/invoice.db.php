<?PHP
if( !defined("HYN")) { exit; }
 
class invoice_ extends ModuleRecord {
	
	private $totals			= false;
	
	protected static function _db_table()
	{
		return "m_adm_invoice";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",		// id
			"invoicenr"		=> "string",
			"relation"		=> "integer",
			"incoming"		=> "boolean",
			"created"		=> "datetime",
			"updated"		=> "datetime",
			"state"			=> "integer",
		);
	}
	protected static function _db_join_many() {
		return array(
				array(
					"foreign_class"		=> "relation",
					"foreign_key"		=> "id",
#					"foreign_alias"		=> "relation",
					"own_key"			=> "relation",
					"multi"				=> false,
					"child_name"		=> "relation"
				),
				array(
					"foreign_class"		=> "invoice_item",
					"foreign_key"		=> "invoice",
#					"foreign_alias"		=> "item",			// cannot use foreign_alias if multi is set and child-name is set
					"own_key"			=> "id",
					"multi"				=> true,
					"child_name"		=> "items"
				),
				array(
					"foreign_class"		=> "invoice_state",
					"foreign_key"		=> "state",
#					"foreign_alias"		=> "item",			// cannot use foreign_alias if multi is set and child-name is set
					"own_key"			=> "state",
					"multi"				=> false,
					"child_name"		=> "state"
				)
		);
	}
	/**
	*	@return status with in/out status
	*/
	public function get_state_formatted_() {
		return $this -> get("state");
	}
	/**
	*	@return invoicenr if has one or concept-id if not
	*/
	public function get_invoicenr() {
		if( $this -> _get("invoicenr") != "" ) {
			return $this -> _get("invoicenr");
		} else {
			return _("concept") . "-" . $this -> id;
		}
	}
	/**
	*	@return total cost of invoice excluding vat
	*/
	public function get_total_exvat_() {
		if( !$this -> totals ) {
			$this -> calc_totals();
		}
		return $this -> totals['exvat'];
	}
	/**
	*	@return total cost of invoice including vat
	*/
	public function get_total_incvat_() {
		if( !$this -> totals ) {
			$this -> calc_totals();
		}
		return $this -> totals['incvat'];
	}
	/**
	*	@return total vat of invoice
	*/
	public function get_total_vat_() {
		if( !$this -> totals ) {
			$this -> calc_totals();
		}
		return $this -> totals['vat'];
	}
	/**
	*	@return calculate and return all total variables in a private var
	*/
	private function calc_totals() {
		if( !$this -> totals && !is_array($this -> totals) ) {
			$this -> totals['exvat']			= 0;
			$this -> totals['incvat']			= 0;
			$this -> totals['vat']				= 0;

			if( count($this -> items) ) { foreach( $this -> items as $item ) {
					$this -> totals['exvat']	+= $item -> get("item_product") -> get("price");
				}
				if( $this -> get("relation") -> currency != HYN_CURRENCY_DEFAULT ) {
					$cur						= currencies::find_one_by_iso( $this -> get("relation") -> currency );
					$this -> totals['exvat']	= $cur -> fromEUR( $this -> totals['exvat'] );
				}
				$this -> totals['incvat']		= round($this -> totals['exvat'] * ( $this -> get("relation") -> vat / 10000 + 1 ));
				$this -> totals['vat']			= $this -> totals['incvat'] - $this -> totals['exvat'];
				return;
			}
		}
	}
}

ModuleRecord::register("invoice");