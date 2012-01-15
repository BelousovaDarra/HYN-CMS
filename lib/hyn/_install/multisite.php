#!/usr/bin/php5 -q
<?PHP
/**
	Root script to setup new multisite
*/
define(	"HYN"	, "cmd" );

require_once "../init.php";

hyn_include( "twig" , "multisite" , "visitor" , "dom" );

// we need a database record to setup
if( !isset($argv) || !count($argv) || !is_int( (int) $argv[1]) || intval( $argv[1] ) == 0 ) { die( "Missing arguments; need a multisite ID" ); }

$ms				= MultiSite::find_one_by_id( (int) $argv[1] );
if( !$ms ) {
	die( sprintf("No entry found in the system for supposed multisite ID %s.",$argv[1]) );
}

hyn_include( "twig" );

$twig			= Twig::get_instance();
$sql			= Twig::parse( __DIR__ . DS . "multisite.sql.twig" , array( "ms" => $ms ) );
var_dump( $sql );

// notify, also through beaconpush about a new portal
