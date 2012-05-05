<?PHP
if(!defined("HYN")) { exit; }

class blog extends module {
	// constructor
	public function _blog() {
		global $SiteVisitor;
		if( $id = $this -> get("path_id") ) {
			// blog article found
			if( $id > 0 && $item = blogArticle::find_one_by_id( $id ) ) {
				$this -> item	= $item;
				return;
			}
			// blog article not found, but admin so we set to 0 and allow creation
			elseif( $SiteVisitor -> get_right( "owner" ) ) {
				$this -> item	= 0;
				return;
			}
			// no article found and just a visitor
			else {
				$this -> item	= false;
			}
		}
		// if no blog article found, show a list of articles or redirect to list?
	}
	
	public function display(  ) {
		if( $this -> item && $this -> item > 0 ) {
			
		}
		elseif( $this -> item && $this -> item == 0 ) {
			
		}
		else {

		}
	}
	
}