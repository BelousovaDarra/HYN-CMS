<?PHP
if( !defined("HYN") ) { exit; }

class MultiSite_ extends AutoRecord {

	protected static function _db()
	{
		return AnewtDatabase::get_connection( "hyn" );
	}
	protected static function _db_table()
	{
		return "system_domain";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",
			"domain"		=> "string",
			"database"		=> "string",
			"owner"			=> "integer",
			"state"			=> "integer",
			"prime"			=> "boolean",
			"settings"		=> "string",
		);
	}
	protected static function _db_join_many() {
		return array(
				array(
					"foreign_class" 	=> "MultiSiteAlias",
					"foreign_key"		=> "aliasof",
					#"foreign_alias"		=> "alias",
					"columns"		=> array( "aliasof" , "alias" ),
					"own_key"		=> "id",
					"multi"			=> true,				# multiple aliases can be defined per domain
					"child_name"		=> "aliases"				# set to be able to request aliases by doing -> get("aliases")
				)
		
		);
	}
	protected static function _db_primary_key() {
		return "id";
	}
	static function getbyhost( $host ) {
		return MultiSite::find_one_by_sql('WHERE `system_domain`.`domain` = ?string? OR `system_domain_alias`.`alias` = ?string?', $host , $host );
	}
	function get_database() {
		if( $this -> _get("database") != "" && $database	= unserialize( $this -> _get("database") )) {
			return $database;
		}
		return false;
	}
	public function setConstants() {
		define(		"HYN_SYSTEM_ID"		, $this -> get("id") );
		if( is_dir( HYN_PATH_DOMAINS . $this -> get("domain") . DS ) ) {
			define(		"HYN_MS_DIR"	, HYN_PATH_DOMAINS . $this -> get("domain") . DS );
			if( is_dir( HYN_MS_DIR . "templates" . DS ) ) {
				define(		"HYN_MS_DIR_TPL"	, HYN_MS_DIR . "templates" . DS );
			}
			if( is_dir( HYN_MS_DIR . "lib" . DS ) ) {
				define(		"HYN_MS_DIR_LIB"	, HYN_MS_DIR . "lib" . DS );
			}
			if( is_dir( HYN_MS_DIR . "modules" . DS ) ) {
				define(		"HYN_MS_DIR_MODULES"	, HYN_MS_DIR . "modules" . DS );
			}
		}
	
	}
	function get_settings($setting=false) {
		if( $this -> _get("database") != "" && $settings = unserialize( $this -> _get("database") )) {
			if( $setting && isset($settings[$setting]) && $settings[$setting] != "" ) {
				return $setting[$setting];
			} elseif( !$setting ) {
				return $settings;
			} 
		}
		return false;
	}
	function get_setting( $setting=false ) {
		if( $setting ) {
			return self::get("settings",$setting);
		}
		return false;
	}
}

AutoRecord::register('MultiSite');