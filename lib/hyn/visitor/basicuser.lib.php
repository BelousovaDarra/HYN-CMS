<?PHP
if(!defined("HYN")) { exit; }

abstract class BasicUser extends AutoRecord {

	
	static public function _autorecord_extra_methods() {
			$methods = parent::_autorecord_extra_methods();

			$methods['find_one_by_un'] = 'public static function find_one_by_un($un) {
					return @@CLASS@@::find_one_by_column("email",$un);
			}';
			return $methods;
	}
	
	final public function get_banned_() {
		if( $this -> get("state") > 0 ) { 		# not banned
			return false;
		} else { return true; } 				# yes banned
	}
	// type of user; 0 indicates system/global user, an higher int indicates the domain affiliated to
	final public function get_type_() {
		if( $this -> _db_table() == "system_user" ) {
			return 0;
		} else {
			return $this -> get('domain');
		}
	}
	final public function get_uid_() {
		return sprintf("%u.%u" , $this -> get("type") , $this -> get("id"));
	}

}
