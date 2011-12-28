<?PHP
if( !defined("HYN")) { exit; }
/**
*		System database settings
*
*/
define(		"HYN_DB_HOST"		, "" );
define(		"HYN_DB_UN"			, "" );
define(		"HYN_DB_PW"			, "" );
define(		"HYN_DB_DB"			, "" );
define(		"HYN_DB_TYPE"		, "" );

/**
*		Show debug info to the following ip's
*			a rudimentary way of showing errors, otherwise system users are shown errors on their own portal
*/
global $debugips;
$debugips						= array( "127.0.0.1" );