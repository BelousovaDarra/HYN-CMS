<?PHP
if(!defined("HYN")) { exit; }

abstract class ModuleRecord extends AutoRecord {
	final protected static function _db()
	{
		return AnewtDatabase::get_connection( "default" );
	}

}
