<?PHP
if(!defined("HYN")) { exit; }

abstract class ModuleRecord extends AutoRecord {
	final protected static function _db()
	{
		return AnewtDatabase::get_connection( "default" );
	}
	static public function _autorecord_extra_methods() {
			$methods = parent::_autorecord_extra_methods();

			$methods['find_one_by_route'] = '
			public static function find_one_by_route($path="/") {
				$class		= get_called_class();
				if( !array_key_exists( "route" , $class::_db_columns() )) { return false; }
				if( !_v($path,"array")) {
					return self::find_one_by_column( "route", $path );
				}
				$i	= count( $path );
				$check	= $path;
				while( $i >= 0 ) {
					$checkroute	= implode( "/" , $check );
					if( $r	 = self::find_one_by_column( "route","/".$checkroute ) ) {
						return $r;
					}
					array_pop( $check );
					$i--;
				}
				if( $r	= self::find_one_by_column( "route", $path."%" )) {
					return $r;
				}
			}
			public static function _table_install() {
				$class		= get_called_class();
				// check for complex type; non-Complex autorecords cannot be installed
				if( !$class::isComplex($class::_db_columns()) ) {
					return false;
				}
				// check whether table is already existing in the database
				if( $class::_table_installed() ) {
					return false;
				}
			}
			public static function _table_installed() {
				$class		= get_called_class();
				$db		= $class::_db();
				$pq		= $db -> prepare( "SHOW TABLES LIKE ?string:table?" );
				$rs		= $pq -> executev( array( "table" => $class::_db_table()) );
				
				return (bool) $rs -> count();
			}';
			return $methods;
	}
}
