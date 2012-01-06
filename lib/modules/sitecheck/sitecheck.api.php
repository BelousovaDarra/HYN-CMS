<?PHP
if( !defined("HYN")) { exit; } 

hyn_include( "social/twitter" );
class sitecheck extends module {
	function _sitecheck(  ) {
		$this -> tf		= new EpiTwitter( 
						MultiSite::setting( "consumerkey" 	, "sitecheck" ) -> get("value") ,
						MultiSite::setting( "consumersecret" 	, "sitecheck" ) -> get("value") ,
						MultiSite::setting( "accesskey" 	, "sitecheck" ) -> get("value") ,
						MultiSite::setting( "accesssecret" 	, "sitecheck" ) -> get("value")
		);
		$ignoreusers		= explode(",",MultiSite::setting( "ignoreusers" , "sitecheck" ) -> get("value"));
		$id			= $this -> get_lastid();
		$mentions		= $this -> tf -> get( "/statuses/mentions.json" , array( "since_id" => $id) );

		if( _v($mentions -> response,"array")) { foreach( $mentions -> response as $mention ) {
			if( in_array($mention['user']['screen_name'],$ignoredusers)) { continue; }

			$users	= $this -> dissectUsers( $mention['text'] );
			$users[]= $mention['user']['screen_name'];
		
			$users	= array_diff( $users , $ignoreusers );
			$users	= $this -> get_users( $users );
			if( !count( $users )) { continue; }
			$this -> dissectTweet( $mention['text'] );
#			$this -> tf -> post( "/statuses/update.json" , array(
#					"status" => $users . ": Uw siteanalyse wordt nu verwerkt. - http://facebook.com/hostingxs" ));
			$this -> resp[]	= $mention['text'];
#			if( $mention['id'] > $id ) {
#				$this -> set_lastid( $id );
#			}

return;
		}}
	}
	private function get_users( $users=array() ) {
		if( _v($users,"array")) { foreach( $users as $u ) {
			$ret[]		= "@".$u;
		}} else { return false; }
		return implode( " " , $ret );
	}
	private function set_lastid($id=0) {
		file_put_contents( __DIR__ . DS . "last.txt" , $id );
	}
	private function get_lastid() {
		return (int) file_get_contents( __DIR__ . DS . "last.txt" );
	}
	private function friendUser( $user ) {
		$this -> tf -> post( "/friendships/create.json" , array( "follow" => true , "screen_name" => $user ));
	}
	private function dissectUsers( $tweet ) {
		$regex = "/(?<user>@[a-z0-9]+)/i";
		preg_match_all( $regex , $tweet , $matches );
		foreach( $matches['user'] as $u ) {
			$ret[] 	= substr($u,1);
		}
		if( isset($ret) && count($ret)) {
			return $ret;
		} else { return array(); }
	}
	function dissectTweet( $tweet ) {
		$domregex		= "/(www\.)?(?<hostname>[a-zA-Z0-9\-]+)(\.(?<tld>[a-zA-Z]{2,6}))(\.(?<tld2>[a-zA-Z]{2,6}))?/i";
		preg_match_all( $domregex , $tweet , $matches );
		foreach( $matches[0] as $m ) {
			if( $m[0] != "" ) {
				$this -> domains[]	= $m;
			}
		}
	}
	# it's an api so do not load any other template; just send to clients ( beaconpush or .. )
	function display() {
		if( _v($this -> domains,"array")) {foreach( $this -> domains as $dom ) {
			if( isset($r[$dom])) { continue; }
			$r[$dom]	= array(
					"pagespeed"		=> $this -> pagespeed( $dom ),
					"ping"			=> $this -> siteping( $dom ),
					"location"		=> $this -> location( $dom )
			);
		}}
		_debug( $r );
		return json_encode( $r );
	}
	private function siteping( $dom ) {
		exec( "ping -c 4 ".$dom , $res );
		$lastline	= array_pop($res);
		$regex		= "/rtt min\/avg\/max\/mdev = (?<min>[0-9\.]+)\/(?<avg>[0-9\.]+)\/(?<max>[0-9\.]+)\/(?<mdev>[0-9\.]+)/i";
		preg_match($regex,$lastline,$ret);
		return $ret;
	}
	private function location( $dom ) {
		# http://freegeoip.net/json/74.200.247.59
		$c = curl_init( "http://freegeoip.net/json/".$dom );
		curl_setopt( $c , CURLOPT_RETURNTRANSFER , true );
		$r	= json_decode( curl_exec( $c ));
		return $r;
	}
	private function pagespeed( $dom ) {
		$c = curl_init( "https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=http://".$dom."&key=".MultiSite::setting( "ga-pagespeedkey" , "sitecheck" ) -> get("value") );
		curl_setopt( $c , CURLOPT_RETURNTRANSFER , true );
		$ret	= json_decode( curl_exec( $c ));
		if( $ret -> responseCode == 200 ) {
			$r['score']		= $ret -> score;
			$r['title']		= $ret -> title;
			$r['pagestats']		= $ret -> pageStats;
		}
		
		return $r;
		#return json_decode(file_get_contents( "https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=".$dom."&key=".MultiSite::setting( "ga-pagespeedkey" , "sitecheck" ) -> get("value")));
		
	}
}