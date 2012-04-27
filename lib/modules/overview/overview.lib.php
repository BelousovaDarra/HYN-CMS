<?PHP
if( !defined("HYN")) { exit; }

class overview extends module {
	protected function _overview() {
		if( HYN_DEBUG ) {
			hyn_include( "pushnotify/client" );
			if( GPC::get_string( "send" )) {
				hyn_include( "pushnotify/push" );
			}
		}
		DOM::set_default_theme();
	}
	/**
	*		shows default frontpage
	*/
	public function display() {
		if( HYN_DEBUG ) {
			hyn_include( "github-api" );
			$github		= new Github_Client;
			$commits 	= $github->getCommitApi()->getBranchCommits('luceos', 'HYN-CMS', 'master');
			$commits	= array_slice( $commits , 0 , 5 );
		}
		return $this -> parseTemplate( "overview" , array( "gitcommits" => $commits ));
	}

} 
