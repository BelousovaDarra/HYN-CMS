<?PHP
if( !defined("HYN")) { exit; }

class login extends module {
	
	protected function SSL() {
		return TRUE;
	}
	public function _login() {
		DOM::set_title( _("Sign up &amp; log in") );
	}
	public function display() {
		global $SiteVisitor;
		$this -> v			= array();
		$this -> v['allow-registration']
							= ( MultiSite::setting( "registration" , "login" ) 
								? (boolean) MultiSite::setting( "registration" , "login" ) -> get("value") 
								: false );
		if( _v( $SiteVisitor -> error['login'] , "array" )) {
			$this -> v['error']['login']		
							= $SiteVisitor -> error['login'];
		}
		if( _v( $SiteVisitor -> error['signup'] , "array" )) {
			$this -> v['error']['su']		
							= $SiteVisitor -> error['signup'];
		}
		return $this -> parseTemplate( "login" , $this -> v );
	}
}