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
			"ssl"			=> "boolean"
		);
	}
	protected static function _db_join_many() {
		return array(
				array(
					"foreign_class" 	=> "MultiSiteAlias",
					"foreign_key"		=> "aliasof",
					#"foreign_alias"	=> "alias",
					"columns"			=> array( "aliasof" , "alias" ),
					"own_key"			=> "id",
					"multi"				=> true,				# multiple aliases can be defined per domain
					"child_name"		=> "aliases"				# set to be able to request aliases by doing -> get("aliases")
				),
				array(
					"foreign_class"		=> "SystemUser",
					"foreign_key"		=> "id",
					"foreign_alias"		=> "owner",
					"columns"			=> array( "id" , "email" , "realname" , "state" , "signedup" , "lastactivity"  , "admin" ),
					"own_key"			=> "owner",
					"multi"				=> false,
					"child_name"		=> "owner"
				)
		
		);
	}
	protected static function _db_primary_key() {
		return "id";
	}
	static function getbyhost( $host ) {
/**		if( strstr($host,".") && $c = explode( '.' , $host ) && count($c) >= 3 && $c[0] == "cp" ) {
			define( "HYNCP"	, true );
			array_shift( $c );
			$host		= implode( "." , $c );
		}*/
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
	static function get_instance() {
		global $MultiSite;
		if( is_a( $MultiSite , "MultiSite" )) {
			return $MultiSite;
		} else {
			return false;
		}
	}
	static public function setting( $setting=false , $module=false ) {
		return SiteSettings::find_one_by_sql( 
			sprintf("WHERE `name` = '%s'". ($module ? " AND `module` = '%s'" : false ) , $setting , ($module ? $module : NULL ))
		);
	}
	static public function validateCreation( $vars=false ) {
		if( !$vars ) { return false; }
		
	}
	public function get_isowner() {
		global $SiteVisitor;
		if( $SiteVisitor -> loggedin() &&
			(
				$this -> get("owner") == $SiteVisitor -> user -> get("uid")
				|| $SiteVisitor -> user -> get("admin")
			)
		) {
			return true;
		}
		return false;
	}
}

AutoRecord::register('MultiSite');