<?PHP

if( !defined( "HYN" )) { exit; }
header( "Access-Control-Allow-Origin: http://rohyn.nl" );

/** setup */
require_once "constants.php";
require_once "config.php";

if( isset($debugips) && in_array($_SERVER['REMOTE_ADDR'],$debugips)) {
	define( "HYN_DEBUG"	, true );
	error_reporting( E_ALL );
} else { 
	define("HYN_DEBUG" , false );
}

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

anewt_include( "autorecord" , "gpc" );

/** 
*	hostname identification 
*/
hyn_include( "multisite" );
/**
*	as long as script is not run on commandline, contine
*/

if( HYN != "cmd" ) {
	hyn_include( "visitor" );
	
	$MultiSite			= MultiSite::getbyhost($_SERVER['HTTP_HOST']);
	// otherwise default to prime site
	if( !$MultiSite ) {
		$MultiSite 		= MultiSite::find_one_by_column("prime",true);
	}
	if( !$MultiSite ) {
		if( HYN_DEBUG ) {
			exit(sprintf("<h1>System malfunctioned</h1><pre>No sites in system_domain table: %s - %s</pre>.",__FILE__,__LINE__));
		}
		exit(sprintf("<pre>System error, our apologies for the inconvenience [%s].</pre>",__LINE__));
	}
	// setup defined dirs
	global $MultiSite;
	$MultiSite -> setConstants();
	if( HYN_SYSTEM_ID && $db = $MultiSite -> get("database") ) {
		
		AnewtDatabase::setup_connection( array(
			"type"			=> (isset( $db['type']) ? $db['type'] : HYN_DB_TYPE),
			"database"		=> $db['db'],
			"hostname"		=> $db['host'],
			"username"		=> $db['un'],
			"password"		=> $db['pw']
		)	, "default" );
	} else {
		if( HYN_DEBUG ) {
			exit(sprintf("<h1>System malfunctioned</h1><pre>Database from portal could not be setup: %s - %s</pre>.",__FILE__,__LINE__));
		}
		exit(sprintf("<pre>System error, our apologies for the inconvenience [%s].</pre>",__LINE__));	
	}
	
	/**
	*		MultiSite identification is now successful
	*		
	*		next: 
	*			see if a user is active
	*			identify paths & load requested url
	*/
	
	# starts visitor object and loads saved session if possible
	# also begins analytics tracking
	$SiteVisitor		= new SiteVisitor;
	
	hyn_include( "dom" );
	hyn_include( "module" );
	hyn_include( "routing" );
	
	hyn_include( "twig" );

	# load url and requested module or load overview module
	routing::init();
	# throw the content to the browser
	routing::flush();
}