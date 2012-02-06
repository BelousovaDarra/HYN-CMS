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
			}';
			return $methods;
	}
}
