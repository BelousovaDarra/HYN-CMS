<?PHP

if(!defined("HYN")) { exit; }
/**
	moduleCP is an extendable object used to create admin interfaces for modules
		see the functions for default functionality
*/


abstract class moduleCP extends module {
	/**		function to parse the sidemenu with options
	*/
	final public function sidemenu() {
		
	}
	/**		settings returns an array of settings; with value types, required and how it matches
	*/
	abstract protected function settings();
	/**		name returns the public name shown to the user
	*/
	abstract protected function name();
	/**		description returns the explanation of what the module does
	*/
	abstract protected function description();
}