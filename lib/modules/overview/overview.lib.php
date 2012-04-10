<?PHP
if( !defined("HYN")) { exit; }

class overview extends module {
	protected function _overview() {
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
		}
		return $this -> parseTemplate( "overview" , array( "gitcommits" => array_slice($commits,0,5) ));
	}
} 
