<?PHP
if(!defined("HYN")) { exit; }


class googleAuth {
	function __construct(  ) {
		$this -> uri			= "https://accounts.google.com/o/oauth2/auth";
		$this -> profile		= "https://www.googleapis.com/auth/userinfo.profile";
		$this -> email			= "https://www.googleapis.com/auth/userinfo.email";
		$this -> clientid		= "903302914705.apps.googleusercontent.com";
		$this -> redirect		= "https://hyn.me/oauth2callback";
	}
	public function sendAuthRequest() {
		
	}
	private function buildURI( $state="auth" ) {
		return sprintf( "%s?scope=%s&state=%s&redirect_uri=%s&response_type=code&client_id=%s" 
			, $this -> uri
			, urlencode(implode( " " , array( $this -> profile , $this -> email )))
			, $state
			, $this -> redirect
			, $this -> clientid
		);
	}
} 
