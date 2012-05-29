<?PHP

if( !defined("HYN")) { exit; }


class pushclient {
	public function __construct() {
		global $SiteVisitor;
		if( !SiteSetting( "apikey" , "pusher" )) {
			return false;
		}
		$this -> cp 			= false;
		// array of key, value with channel variable and value
		$this -> channels		= array(
				"global"	=> "global",
				"siteid"	=> sprintf( "ms-%d" 	, HYN_SYSTEM_ID ),
				"userid"	=> sprintf( "user-%s"	, ($SiteVisitor -> loggedin() ? $SiteVisitor -> user -> get("uid") : "guest" ))
		);
	}
	public function addChannel( $name , $value ) {
		$this -> channels[$name]	= $value;
	}
	public function enable( ) {
		global $SiteVisitor;
		if( HYN_URI_SSL ) {
			DOM::set_js( "https://d3dy5gmtp8yhk7.cloudfront.net/1.12/pusher.min.js" );
		} else {
			DOM::set_js( "http://js.pusher.com/1.12/pusher.min.js" );
		}
		$chan			= "";
		foreach( $this -> channels as $name => $value ) {
			$chan		.= sprintf("
				var push_%s					= pusher.subscribe( '%s' );" , $name , $value);
		}
		$presence		= "";
		if( $SiteVisitor -> loggedin() ) {
			$presence	.= "
				var push_user 					= pusher.subscribe( 'presence-users' );";
			if( $SiteVisitor -> isadmin() ) {
			$presence	.= sprintf("
	push_user.bind('pusher:subscription_succeeded', function(members) {
		push_user.members.each(function(member) {
			addMember2Pusher( member );
			
		});
		push_user.bind( 'pusher:member_added' , function( member ) {
			addMember2Pusher( member );
		});
	});
	push_user.bind('puser:subscription_removed', function( member ) {
		removeMemberFromPusher( member );
	});
	function removeMemberFromPusher( member ) {
		if( jQuery( 'table#pusher-users tr#pusher-member-' + member.id ).length > 0 ) {
			jQuery( 'table#pusher-users tr#pusher-member-' + member.id ).remove();
		}

	}
	function addMember2Pusher( member ) {
		if( jQuery( 'table#pusher-users' ).length > 0 ) {
			if( '%s' == member.id ) {
				var personicon		= 'icon-user';
			} else {
				var personicon		= 'icon-volume-off';
			}
			
			var elm				= jQuery( 'table#pusher-users #pusher-example').clone();

			jQuery( elm ).find( '.icon i' ).attr( 'class' , personicon );
			jQuery( elm ).attr( 'id' , 'pusher-member-' + member.id );
			jQuery( elm ).find( '.name' ).html( member.info.realname );
			jQuery( elm ).find( '.name' ).attr( 'data-original-title' , member.info.realname );
			jQuery( elm ).find( '.name' ).attr( 'data-content' , 'User ' + member.id + ', known with e-mail address ' + member.info.email + '' );
			jQuery( elm ).find( '.name' ).popover({
				placement: function(pop,elm) {
					return jQuery(elm).attr('data-popover-placement');
				}
			});
			jQuery( elm ).find( '.website' ).html( member.info.website.domain );
			jQuery( 'table#pusher-users' ).append( elm );

			jQuery( elm ).slideDown();
		}
	}
			"
			,$SiteVisitor -> user -> get("uid"));
			}
		}
		DOM::add_js( 
			sprintf('
				var pusher 					= new Pusher( "%s" );
				%s
				%s
				
				pusher.bind( "alert" , function(data) {
					alert(data);
				});
				pusher.bind( "show-modal" , function(data) {
					jQuery("body").append(data).modal("show");
				});
			'
			,SiteSetting( "apikey" , "pusher" )
			,$chan
			,$presence
			) 
		, 'body' );
	}
}