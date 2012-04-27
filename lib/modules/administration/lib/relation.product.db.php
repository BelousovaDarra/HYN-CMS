<?PHP
if( !defined("HYN")) { exit; }

class relation_product_ extends ModuleRecord {
	protected static function _db_table()
	{
		return "m_adm_relation_product";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",
			"product"		=> "int",			// @rel: product.id
			"relation"		=> "int",			// @rel: relation.id
			"dependson"		=> "int",			// @rel: relation_product.id	> this product can be dependant on another product
												//								> if you want to remove this product the `dependson` must be gone
			"added"			=> "datetime",		// when the product was added to the customer
			"ended"			=> "datetime",		// when the product was removed from customer

			// the following are actually duplicated from product or overruled
			"billperiod"	=> "string",		// day, week, months
			"billunits"		=> "integer",		// number of previous period
			"price"			=> "int",			// must be set here, because prices from product table can fluctuate
												// this price is always exvat and ex reductions (from different table)
			"description"	=> "string",		// extra explanatory text of this customers' product (eg when or where)
		);
	}
	protected static function _db_skip_on_insert() {
		return array( "dependson" , "description" );
	}
	function delete() {
		if( $this -> get("dependson") && relation_product::find_one_by_id( $this -> get("dependson") )) {
			trigger_error( sprintf("Cannot remove %s %d because dependant item %d has not yet been removed." , __CLASS__ , $this -> get("id") , $this -> get("dependson") ) );
		}
		$this -> set( "ended" , AnewtDatetime::now() );
		$this -> save();
	}
	static function find_by_relation( $relationID ) {
		return relation_product::find_by_column( "relation" , $relationID );
	}
	static function find_active_by_relation( $relationID ) {
		return relation_product::find_by_sql( sprintf("WHERE relation = %d AND ( ended > NOW() OR ended IS NULL )" , $relationID ) );
	}
	
}

ModuleRecord::register( "relation_product" );