<?PHP

if( !defined("HYN")) { exit; }


class pushserver {
	function __construct() {
		if( SiteSetting( "apikey" , "pusher" ) && SiteSetting( "secret" , "pusher" ) && SiteSetting( "appid" , "pusher" ) ) {
			require_once "serve" . DS . "lib" . DS . "Pusher.php";
			$this -> server		= new Pusher( 
							SiteSetting( "apikey" , "pusher" ) , 
							SiteSetting( "secret" , "pusher" ) , 
							(int) SiteSetting( "appid" , "pusher" ) );
		} else { return false; }
	}
	function authAjax() {
		global $SiteVisitor,$MultiSite;
		if( $SiteVisitor -> loggedin() && GPC::post_string( "socket_id" , false ) && GPC::post_string( "channel_name" , false ) ) {
			$presenceregex					= "/presence-(.*)/";
			if( preg_match( $presenceregex , GPC::post_string( "channel_name" ))) {
				$data					= $SiteVisitor -> user -> to_array( array("realname","email","signedup") );
				$data['website']			= $MultiSite -> to_array( array( "id" , "domain" , "owner" ) );
				echo $this -> server -> presence_auth( 
									GPC::post_string( "channel_name" ) , 
									GPC::post_string( "socket_id" ) ,
									$SiteVisitor -> user -> get("uid"),
									$data
				);
			} else {
				echo $this -> server -> socket_auth( GPC::post_string( "channel_name" ) , GPC::post_string( "socket_id" ) );
			}
			exit;
		}
		header('', true, 403);
  		echo "Forbidden";
		exit;
	}

}


/**	$pusher		= new Pusher( 
							SiteSetting( "apikey" , "pusher" ) , 
							SiteSetting( "secret" , "pusher" ) , 
							(int) SiteSetting( "appid" , "pusher" ) );
	
	$pusher -> trigger ('default' , 'message' , 'test' );**/