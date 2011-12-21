<?PHP

if( !defined( "HYN" )) { exit; }

$debugips		= array( "85.223.52.86" , "83.160.60.188" );

if( in_array($_SERVER['REMOTE_ADDR'],$debugips)) {
	define( "HYN_DEBUG"	, true );
	error_reporting( E_ALL );
} else { define("HYN_DEBUG" , false ); }

/** setup */
require_once "constants.php";
require_once "config.php";

/** anewt library included */
require_once HYN_PATH_ANEWT . "anewt.lib.php";

/** create inclusion function for hyn path ; hyn_include() */
create_include_function( "hyn" , HYN_PATH_HYN );


hyn_include( "functions" );

/** setup core database */
anewt_include( "database.new" );
AnewtDatabase::setup_connection( array(
	"type"			=> HYN_DB_TYPE,
	"database"		=> HYN_DB_DB,
	"hostname"		=> HYN_DB_HOST,
	"username"		=> HYN_DB_UN,
	"password"		=> HYN_DB_PW,
	"persistent"		=> false
)	, "hyn" );

anewt_include( "autorecord" );

/** 
*	hostname identification 
*/
hyn_include( "multisite" );
$ms			= MultiSite::getbyhost($_SERVER['HTTP_HOST']);
// otherwise default to prime site
if( !$ms ) {
	$ms 		= MultiSite::find_one_by_column("prime",true);
}
if( !$ms ) {
	if( HYN_DEBUG ) {
		exit(sprintf("<h1>System malfunctioned</h1><pre>No sites in system_domain table: %s - %s</pre>.",__FILE__,__LINE__));
	}
	exit(sprintf("<pre>System error, our apologies for the inconvencience.</pre>"));
}

// setup defined dirs
$ms -> setConstants();

if( HYN_SYSTEM_ID && $db		= $ms -> get("database") ) {
	AnewtDatabase::setup_connection( array(
		"type"			=> HYN_DB_TYPE,
		"database"		=> $db['db'],
		"hostname"		=> $db['host'],
		"username"		=> $db['un'],
		"password"		=> $db['pw']
	)	, "default" );
}

/**
*		MultiSite identification is now successful
*		
*		next: 
*			see if a user is active
*			identify paths & load requested url
*/

hyn_include( "visitor" );
visitor::init();

hyn_include( "routing" );
routing::init();
