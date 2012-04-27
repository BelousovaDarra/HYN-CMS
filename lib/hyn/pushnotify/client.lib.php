<?PHP

if( !defined("HYN")) { exit; }

if( SiteSetting( "apikey" , "pusher" ) ) {
		
	
	
	if( HYN_URI_SSL ) {
		DOM::set_js( "https://d3dy5gmtp8yhk7.cloudfront.net/1.11/pusher.min.js" );
	} else {
		DOM::set_js( "http://js.pusher.com/1.11/pusher.min.js" );
	}
	#DOM::set_js( __DIR__ . DS . "lib" . DS . "src" . DS . "pusher.js" );
	
	DOM::add_js( '
		var pusher 		= new Pusher( "'.SiteSetting( "apikey" , "pusher" ).'" );
		var central 	= pusher.subscribe( "default" );
		var sitechan	= pusher.subscribe( "ms-'.HYN_SYSTEM_ID.'" );
		pusher.bind( "message" , function(data) {
			alert(data);
		});
	
	' , 'body' );

}