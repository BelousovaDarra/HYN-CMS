<?PHP
if( !defined("HYN")) { exit; }

class overview extends module {
	protected function _overview() {

		DOM::set_default_theme();
	
#		DOM::add_js( 'jQuery("input[name=\'free-trial\']").click(function() {jQuery("input[name=\'payment\'][value=0]").attr("disabled",false);});' , "body" );
	}
	/**
	*		information pages ; uses page class?
	*/
	public function discover() {
		if( !class_exists("page")) {
			# [TODO] show error
		}
		// load page
		$p 		= new page;
		return $p -> display();
	}
	/**
	*		shows login
	*/
	public function login() {
		$l		= new login;
		return $l -> display();
	}
	/**
	*		shows all options and store it into session and server to be used if order is not finished
	*/
	public function decide() {
		
	}
	/**
	*		deploy shows registration steps and sets up everything for (almost) instant use
	*/
	public function deploy() {
		
	}
	/**
	*		shows default frontpage
	*/
	public function display() {
		# start sparking
		if( $this -> route -> path[0] == "spark" ) {
			// cloud spark form filled in and submitted
			if( GPC::post_string( "spark" ) ) {
				$fields		= $_POST;
				
				
				
				return $this -> parseTemplate( "review" , array( "fields" => $fields ) );
			}
			return $this -> parseTemplate( "startcloud" , array( "domain" => $this -> route -> path[1] ) );
		} else
		# domain check
		if( isset( $this -> domain )) {
			return $this -> parseTemplate( "domaincheck" , array( "domain" => $this -> domain[0] ) );
		} else {
			return $this -> parseTemplate( "overview" );
		}
	}
} 
