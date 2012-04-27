<?PHP

if( !defined("HYN")) { exit; }

if( SiteSetting( "apikey" , "pusher" ) && SiteSetting( "secret" , "pusher" ) && SiteSetting( "appid" , "pusher" ) ) {

	require_once "serve" . DS . "lib" . DS . "Pusher.php";
	
/**	$pusher		= new Pusher( 
							SiteSetting( "apikey" , "pusher" ) , 
							SiteSetting( "secret" , "pusher" ) , 
							(int) SiteSetting( "appid" , "pusher" ) );
	
	$pusher -> trigger ('default' , 'message' , 'test' );**/


}