<?PHP
if(!defined("HYN")) { exit; }

class BasicUser_ extends AutoRecord {
	static function find_one_by_un( $un ) {
		return self::find_one_by_column( "email" , $un );
	}
	public function get_banned_() {
		if( $this -> get("state") > 0 ) { # not banned
			return false;
		} else { return true; } # yes banned
	}
}
AutoRecord::register('BasicUser');