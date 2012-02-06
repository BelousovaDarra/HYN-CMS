<?PHP
if( !defined("HYN")) { exit; }

class page extends module {
	public function _page() {
		$this -> path		= $this -> route -> path;
		$this -> page		= pages::find_one_by_route( $this -> path );
	}
	public function display() {
		if( !$this -> page ) {
			# if admin
			#DOM::set_wysiwyg();
			
			# [TODO] show 404
		}
		DOM::set_title( 
				$this -> page -> title . 
				( $this -> page -> subtitle && strlen( $this -> page -> title) < 100 
					? " - " . $this -> page -> subtitle 
					: false 
				)
		);
		return $this -> parseTemplate( "page" , array( "page" => $this -> page ));
	}
}