<?PHP
if( !defined("HYN")) { exit; } 

hyn_include( "social/twitter" );
class sitecheck extends module {
	function _sitecheck(  ) {
		
		
		if( GPC::get_string( "update" ) == 1) {
			$this -> savelastId		= __DIR__ . DS . "checkedforhost" . DS ."setting.txt";
			trigger_error( "UPDATE: last checked twitter mention: ".$this -> get_lastid() );
			if( time() - filemtime( $this -> savelastId ) >= 15 ) {
				while( true ) {
					trigger_error( "UPDATE: starting queryTwitter" );
					touch( $this -> savelastId );
					$this -> queryTwitter();
					$i = 1;
					while( $i < 6 ) {
						touch( $this -> savelastId );
						sleep( 5 );
						$i++;
					}
					
				}
			} else {
				trigger_error( "UPDATE: ending directly, too quickly rechecked" );
			}
			exit;
		}
		if( $this -> route -> route -> get("function") == "show" ) {
			hyn_include( "bootstraps/twitter" );
			DOM::set_css( "style.css" );
			DOM::set_title( "@domeinvrij" );
			DOM::set_meta( "name" , "generator" , "HYN.me" );

			DOM::add_js( 'jQuery("[data-popover-placement=\'right\']").popover({ live: true, html: true, title: \'data-popover-title\', content: \'data-popover-content\' });' , "body" );
			DOM::add_js( 'jQuery("[data-popover-placement=\'left\']").popover({ live: true, html: true, title: \'data-popover-title\', content: \'data-popover-content\', placement: \'left\' });' , "body" );
			DOM::add_js( 'jQuery("[data-twipsy-content]").twipsy({ live: true, html: true, title: \'data-twipsy-content\' });' , "body" );
		}
		if( $last = GPC::get_int("last")) {
			trigger_error( gethostbyaddr($_SERVER['REMOTE_ADDR']) . " requested last since ".date("Y/m/d H:i:s",$last) );	# show log notification
			$last			= AnewtDatetime::parse_timestamp( $last );

			$this -> publish	= websitecheck::find_by_sql("WHERE updated > ?datetime?",$last);
			rsort( $this -> publish );
			return;
		}
		if( $item = GPC::get_string("host")) {
			$this -> publish[]	= websitecheck::find_one_by_column( "domain" , $item );
			return;
		}
		if( $item = GPC::get_int("id") ) {
			$this -> publish[]	= websitecheck::find_one_by_id( $item );
			return;
		}
		trigger_error( gethostbyaddr( $_SERVER['REMOTE_ADDR'] ) . " requested last 10" );
		$this -> publish		= websitecheck::find_by_sql("WHERE 1 ORDER BY updated DESC LIMIT 10");
		return;
	}
	public function show() {
		foreach( $this -> publish as $p ) {
			$p -> json	= unserialize( $p -> get("json") );
			$this -> d[]	= $p;
		}
		return $this -> parseTemplate( "show" , array( "domains" => $this -> d ) );
	}
	private function queryTwitter(  ) {
		
		try {
			$this -> tf		= new EpiTwitter( 
						MultiSite::setting( "consumerkey" 		, "sitecheck" ) -> get("value") ,
						MultiSite::setting( "consumersecret" 		, "sitecheck" ) -> get("value") ,
						MultiSite::setting( "accesskey" 		, "sitecheck" ) -> get("value") ,
						MultiSite::setting( "accesssecret" 		, "sitecheck" ) -> get("value")
			);
		} catch( Exception $e ) {
			trigger_error( "UPDATE: twitter unavailable during login" );
			$i = 1;
			while( $i < 12 ) {
				touch( $this -> savelastId );
				sleep( 5 );
				$i++;
			}
			return;
		}
		$ignoreusers		= explode(",",MultiSite::setting( "ignoreusers" , "sitecheck" ) -> get("value"));
		$id			= $this -> get_lastid();
		unset( $this -> domains , $mentions );
		try {
			$mentions		= $this -> tf -> get( "/statuses/mentions.json" , array( "since_id" => $id , "include_entities" => true , "count" => 10 ) );
		} catch ( Exception $e ) {
			trigger_error( "UPDATE: twitter unavailable during mentions fetch" );
			$i = 1;
			while( $i < 12 ) {
				touch( $this -> savelastId );
				sleep( 5 );
				$i++;
			}
			return;
		}
		
		$cnt			= 0;
		if( _v($mentions -> response,"array")) { foreach( $mentions -> response as $mention ) {
			trigger_error( "UPDATE: looping through mentions: ".$mention['id'] );
			touch( $this -> savelastId );
			if( $mention['id'] > $id ) {
				$this -> set_lastid( $mention['id'] );
				$id = $mention['id'];
			}
#			if( in_array($mention['user']['screen_name'],$ignoredusers)) { continue; }

			$users	= $this -> dissectUsers( $mention['text'] );
			$users[]= $mention['user']['screen_name'];
		
			$users	= array_diff( $users , $ignoreusers );
			$usersarr[$mention['id']] = $users;
			$users[$mention['id']]	= $this -> get_users( $users );
			if( !count( $users[$mention['id']] )) { continue; }

			$this -> dissectTweet( $mention['entities']['urls'] , $mention );
			if( !count( $this -> domains ) ) { continue; }


#			$this -> tf -> post( "/statuses/update.json" , array(
#					"status" => $users . ": Uw siteanalyse wordt nu verwerkt. - http://facebook.com/hostingxs" ));
			$this -> resp[]	= $mention['text'];

			$cnt++;
			touch( $this -> savelastId );
#			if( count($this -> domains) >= 4 ) {return;}
		}}
#_debug( $this -> domains );

		if( isset($this -> domains) && _v($this -> domains,"array")) {
			touch( $this -> savelastId );
			$this -> domains	= array_unique( $this -> domains );
			foreach( $this -> domains as $dom ) {
				if( preg_match( "/hostingxs\./i" , $dom )) { continue; }
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
				$this -> read[$mention['id']][$dom]	= array(
							"domain"		=> $dom,
							"pagespeed"		=> $this -> pagespeed( $dom ),
	#						"ping"			=> $this -> siteping( $dom ),
							"location"		=> $this -> location( $dom ),
							"availability"		=> $this -> availability( $dom ),
							"stamp"			=> time()
				);
				$wsc		-> set('json' , serialize( $this -> read[$mention['id']][$dom] ));
				$wsc		-> save( ( $new ? true : null ) );
				if( $this -> read[$mention['id']][$dom]['pagespeed'] != "" ) {
					$score 		= $dom." heeft de score: ".$this -> read[$mention['id']][$dom]['pagespeed']['score']." (http://domeinvrij.hostingxs.nl/?host=".$dom.")";
				} else {
					$score 		= $dom." is beschikbaar (http://domeinvrij.hostingxs.nl/?host=".$dom.")";
				} 
				

				
				touch( $this -> savelastId );
				sleep(1);
				foreach( $usersarr[$mention['id']] as $u ) {
					$this -> friendUser( $u );
					$this -> dmUser( $u , "Bedankt dat wij uw site mochten controleren; ".$score."!" );
				}
				unset( $wsc , $score );
		}}
	}
	private function get_users( $users=array() ) {
		if( _v($users,"array")) { foreach( $users as $u ) {
			$ret[]		= "@".$u;
		}} else { return false; }
		return implode( " " , $ret );
	}
	private function set_lastid($id=1) {
		file_put_contents(  $this -> savelastId , $id );
	}
	private function get_lastid() {
		if( is_file( $this -> savelastId )) {
			return (int) file_get_contents( $this -> savelastId );
		} else { return 1; }
	}
	private function friendUser( $user ) {
		try {
			$this -> tf -> post( "/friendships/create.json" , array( "follow" => true , "screen_name" => $user ));
		} catch( Exception $e ) {
			trigger_error( "UPDATE: could not create friendship with user ".$user ." / reason: ".$e -> getMessage() );
			return;
		} 
	}
	private function dmUser( $user , $msg ) {
		try {
			$this -> tf -> post( "/direct_messages/new.json" , array( "screen_name" => $user , "text" => $msg ));
		} catch( Exception $e ) {
			trigger_error( "UPDATE: could not send DM to user ".$user ." / reason: ".$e -> getMessage() );
			return;
		}
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
	function dissectTweet( $urls , $mention ) {
		$text	= $mention['text'];
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
//		rsort( $this -> publish );
		$i			= 0;
		foreach( $this -> publish as $r ) {
			$ret[$i]		= unserialize($r -> get("json"));
			$i++;
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
	private function freegeoip( $dom ) {
		# http://freegeoip.net/json/74.200.247.59
		$c = curl_init( "http://freegeoip.net/json/".$dom );
		curl_setopt( $c , CURLOPT_RETURNTRANSFER , true );
		$r	= json_decode( curl_exec( $c ));
		if( isset($r -> country_code) && $r -> country_code != "" ) {
			$r -> country_code	= strtolower($r -> country_code);
		} else { $r = ""; }
		return $r;
	}
	private function location( $dom ) {
		$c 	= curl_init( "http://api.ipinfodb.com/v3/ip-country/?key=".MultiSite::setting( "infodb" , "sitecheck" ) -> get("value")."&ip=".$dom."&format=json" );
		curl_setopt( $c , CURLOPT_RETURNTRANSFER , true );
		$r	= json_decode( curl_exec( $c ));
		if( $r -> statusCode == 200 && isset($r -> countryCode) && $r -> countryCode != "" ) {
			$r -> country_code	= strtolower($r -> countryCode );
		} else { $r = $this -> freegeoip($dom); }
		return $r;
	}
	private function pagespeed( $dom ) {
		$c = curl_init( "https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=http://".$dom."&key=".MultiSite::setting( "ga-pagespeedkey" , "sitecheck" ) -> get("value") );
		curl_setopt( $c , CURLOPT_RETURNTRANSFER , true );
		$ret				= json_decode( curl_exec( $c ));
		if( $ret && $ret -> responseCode == 200 ) {
			$score			= (string) $ret -> score;
			$lastnr			= intval(substr( $score , -1 ));
			$score			= substr( $score , 0 , -1 );
			if( $lastnr && $lastnr > 0 ) {
				$score		= $score .",". (string) $lastnr;
			}

			$r['score']		= $score;
			$r['title']		= $ret -> title;
//			$r['pagestats']		= $ret -> pageStats;
		} else { $r = ""; }
		
		return $r;
		
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