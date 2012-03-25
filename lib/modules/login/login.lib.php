<?PHP
if( !defined("HYN")) { exit; }

class login extends module {
	
	protected function SSL() {
		return TRUE;
	}
	public function _login() {
		global $SiteVisitor;
		if( GPC::post_string("login-user") ) {
			if( $SiteVisitor -> login() ) {
				# show public notification welcome	
			}
		}
		// register
		elseif( GPC::post_string("register-user") ) {
			if( $SiteVisitor -> register() ) {
				# do something after registering
			}
		}
		
		DOM::set_title( _("Sign up &amp; log in") );
	}
	public function display($v=array()) {
		global $SiteVisitor;
		$this -> v			= $v;
		$this -> v['allowregistration']
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