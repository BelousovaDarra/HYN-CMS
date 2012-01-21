<?PHP
if( !defined("HYN")) { exit; } 

hyn_include( "social/twitter" );
class sitecheck extends module {
	function _sitecheck(  ) {
		if( GPC::get_string( "update" )) {
			while( true ) {
				$this -> queryTwitter();
				sleep( 10 );
			}
			exit;
		}

		if( $last = GPC::get_int("last")) {

			$last			= AnewtDatetime::parse_timestamp( $last );
			$this -> publish	= websitecheck::find_by_sql("WHERE updated > ?datetime? OR created > ?datetime?",$last,$last);
			rsort( $this -> publish );
			return;
		}
		$this -> publish		= websitecheck::find_by_sql("WHERE 1 ORDER BY updated DESC LIMIT 10");
		return;
	}
	private function queryTwitter(  ) {
		$this -> savelastId		= "setting.txt";
		try {
			$this -> tf		= new EpiTwitter( 
						MultiSite::setting( "consumerkey" 		, "sitecheck" ) -> get("value") ,
						MultiSite::setting( "consumersecret" 		, "sitecheck" ) -> get("value") ,
						MultiSite::setting( "accesskey" 		, "sitecheck" ) -> get("value") ,
						MultiSite::setting( "accesssecret" 		, "sitecheck" ) -> get("value")
		);
		} catch( Exception $e ) {
			return;
		}
		$ignoreusers		= explode(",",MultiSite::setting( "ignoreusers" , "sitecheck" ) -> get("value"));
		$id			= $this -> get_lastid();
		$mentions		= $this -> tf -> get( "/statuses/mentions.json" , array( "since_id" => ( $id + 1 ) , "include_entities" => true , "count" => 20 ) );
//		rsort( $mentions );
		$cnt			= 0;
		if( _v($mentions -> response,"array")) { foreach( $mentions -> response as $mention ) {
			if( $mention['id'] > $id ) {
				$this -> set_lastid( $mention['id'] );
				$id = $mention['id'];
			}
#			if( in_array($mention['user']['screen_name'],$ignoredusers)) { continue; }

			$users	= $this -> dissectUsers( $mention['text'] );
			$users[]= $mention['user']['screen_name'];
		
			$users	= array_diff( $users , $ignoreusers );
			$users	= $this -> get_users( $users );
			if( !count( $users )) { continue; }
			$this -> dissectTweet( $mention['entities']['urls'] , $mention['text'] );
			if( !count( $this -> domains ) ) { continue; }
#			$this -> tf -> post( "/statuses/update.json" , array(
#					"status" => $users . ": Uw siteanalyse wordt nu verwerkt. - http://facebook.com/hostingxs" ));
			$this -> resp[]	= $mention['text'];

			$cnt++;
#			if( count($this -> domains) >= 4 ) {return;}
		}}
#_debug( $this -> domains );
		
		if( isset($this -> domains) && _v($this -> domains,"array")) {
			$this -> domains	= array_unique( $this -> domains );
			foreach( $this -> domains as $dom ) {
			if( $wsc = websitecheck::find_one_by_column("domain",$dom)) {
				if( AnewtDatetime::timestamp($wsc -> get("updated")) >= (time() + (3600 * 24))) { continue; }
				$wsc -> set("updated"	,AnewtDatetime::now());
				$new	= false;
			} else {
				$wsc	= new websitecheck;
				$new	= true;
				$wsc -> set( 'domain' , $dom );
				$wsc -> set( 'created', AnewtDatetime::now());
				$wsc -> set( 'updated', AnewtDatetime::now()); 
			}
			$wsc		-> set('json' , serialize(array(
						"domain"		=> $dom,
						"pagespeed"		=> $this -> pagespeed( $dom ),
						"ping"			=> $this -> siteping( $dom ),
						"location"		=> $this -> location( $dom ),
						"availability"		=> $this -> availability( $dom )
			)));
			$wsc		-> save();
			unset( $wsc );
		}}
	}
	private function get_users( $users=array() ) {
		if( _v($users,"array")) { foreach( $users as $u ) {
			$ret[]		= "@".$u;
		}} else { return false; }
		return implode( " " , $ret );
	}
	private function set_lastid($id=0) {
		file_put_contents( __DIR__ . DS . "checkedforhost" . DS . $this -> savelastId , $id );
	}
	private function get_lastid() {
		if( is_file( __DIR__ . DS . "checkedforhost" . DS . $this -> savelastId )) {
			return (int) file_get_contents( __DIR__ . DS . "checkedforhost" . DS . $this -> savelastId );
		} else { return 1; }
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
	function dissectTweet( $urls , $text ) {
		$excludes = array( "t.co" , "on.fb.me" , "ow.ly" , "bit.ly" );
		$domregex		= "/(www\.)?(?<hostname>[a-zA-Z0-9\-]+)(\.(?<tld>[a-zA-Z]{2,6}))(\.(?<tld2>[a-zA-Z]{2,6}))?/i";

		if( _v($urls,"array")) {foreach( $urls as $url ) {
			$uri = $url['display_url'];


			preg_match_all( $domregex , $uri , $matches );
			foreach( $matches[0] as $m ) {
				if( $m[0] != "" && !in_array( $m , $excludes ) ) {
					$this -> domains[]	= $m;
					$excludes[]		= $m;
				}
			}
		}}
		preg_match_all( $domregex , $text , $matches );
		foreach( $matches[0] as $m ) {
			if( $m[0] != "" && !in_array( $m , $excludes ) ) {
				$this -> domains[]	= $m;
				$excludes[]		= $m;
			}
		}
	}
	# it's an api so do not load any other template; just send to clients ( beaconpush or .. )
	function display() {
		$ret			= array();
		rsort( $this -> publish );
		foreach( $this -> publish as $r ) {
			$ret[]		= unserialize($r -> get("json"));
		}
		return json_encode( $ret );
	}
	private function siteping( $dom ) {
		exec( "ping -c 4 ".$dom , $res );
		$lastline	= array_pop($res);
		$regex		= "/rtt min\/avg\/max\/mdev = (?<min>[0-9\.]+)\/(?<avg>[0-9\.]+)\/(?<max>[0-9\.]+)\/(?<mdev>[0-9\.]+)/i";
		preg_match($regex,$lastline,$ret);
		$ret['avg']	= round( $ret['avg'] );
		return $ret;
	}
	private function location( $dom ) {
		# http://freegeoip.net/json/74.200.247.59
		$c = curl_init( "http://freegeoip.net/json/".$dom );
		curl_setopt( $c , CURLOPT_RETURNTRANSFER , true );
		$r	= json_decode( curl_exec( $c ));
		if( isset($r -> country_code) ) {
			$r -> country_code	= strtolower($r -> country_code);
		} else { $r = ""; }
		return $r;
	}
	private function pagespeed( $dom ) {
		$c = curl_init( "https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=http://".$dom."&key=".MultiSite::setting( "ga-pagespeedkey" , "sitecheck" ) -> get("value") );
		curl_setopt( $c , CURLOPT_RETURNTRANSFER , true );
		$ret	= json_decode( curl_exec( $c ));
		if( $ret && $ret -> responseCode == 200 ) {
			$score			= (string) $ret -> score;
			$lastnr			= intval(substr( $score , -1 ));
			$score			= substr( $score , 0 , -1 );
			if( $lastnr && $lastnr > 0 ) {
				$score		= $score .",". (string) $lastnr;
			}

			$r['score']		= $score;
			$r['title']		= $ret -> title;
			$r['pagestats']		= $ret -> pageStats;
		} else { $r = ""; }
		
		return $r;
		#return json_decode(file_get_contents( "https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=".$dom."&key=".MultiSite::setting( "ga-pagespeedkey" , "sitecheck" ) -> get("value")));
		
	}
	private function availability( $dom ) {
		hyn_include( "hxsapi" );
		$hxs		= new hxsclient( MultiSite::setting( "resellerid" , "hxsclient" ) -> get("value") , MultiSite::setting( "resellerpw" , "hxsclient" ) -> get("value") );
		$ret		= $hxs -> checkDomain( $dom );
		if( $ret[0] -> free ) {
			return $ret[0];
		} else {
			return "";
		} 
	}
}