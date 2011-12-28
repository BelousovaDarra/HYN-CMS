<?PHP

if(!defined("HYN")) { exit; }

class forms {
	final function __construct( $elements=false ) {
		$this -> tpldir		= "forms" . DS ;
		$this -> elements	= $elements;
		$this -> jscalled	= array();
	}
	final public function validate() {
		global $uri;
			$required		= 0;
			$validated		= 0;
			foreach( $this -> elements as $name => $el ) {
				
				if( isset($el['required']) || isset($el['match'])) {
					if( isset($uri -> post[$name])) {
						$validate	= _v($uri -> post[$name],(isset($el['match']) ? $el['match'] : $el['required']));
						
						if( isset($el['required']) && is_bool($validate) && $validate == TRUE ) {
							$validated++;
						} elseif(isset($el['required']) || is_string($validate)) {
							$this -> elements[$name]['error']	= $validate;
							
						}
					} else {
						$this -> elements[$name]['error']	= _("this field is required");
					}
					if( isset( $this -> elements[$name]['example'] ) && isset($this -> elements[$name]['error'])) {
						$this -> elements[$name]['error']	= str_replace( "{example}" , $this -> elements[$name]['example'] , $this -> elements[$name]['error'] );
					}
					if( isset($el['required']) ) {
						$required++;
					}
				}
				if( !isset($uri -> post[$name]) && !isset($el['required'])) {
					continue;
				}
				if( !isset($this -> elements[$name]['error']) && isset($el['_todb']) && $el['_todb'] != true) {
					$f 									= $el['_todb'];
					$in[$name]							= $f($uri -> post[$name]);
					$this -> elements[$name]['value']	= $uri -> post[$name];
				} elseif(!isset($this -> elements[$name]['error']) && isset($el['_todb']) && $el['_todb']) {
					$in[$name]							= (isset($uri -> post[$name]) ? $uri -> post[$name] : false);
					$this -> elements[$name]['value']	= (isset($uri -> post[$name]) ? $uri -> post[$name] : false);
					continue;
				}
			}
			if( $validated == $required ) {
				if( isset($in) && count($in) ) {
					return $in;
				} else {
					return false;
				}
			} else {
				global $notification;
				$notification -> add( __CLASS__ , sprintf(_("Missing required fields, field validation incomplete (%d of %d)."),$validated,$required) , "info" );
				return false;
			}
	}
	final protected function addElement( $name , $element ) {
		$element['fieldname']	= $name;
		$value					= $this -> parseTemplate( $element['tpl'] , $element );
		if( isset($element['fieldset'])) {
			$this -> parse[]	= $this -> parseTemplate( "field.value.fieldset" , 
														 array( 	"name" 	=> $element['name'] , 
																	"value" => $value , 
																	"info" 	=> (isset($element['info']) ? $element['info'] : false),
																	"error"	=> (isset($element['error']) ? $element['error'] : false),
																	"icon"	=> (isset($element['icon']) ? $element['icon'] : false),
																	"element" => $element
														)
			);
		} elseif( isset($element['value-b'])) {
			$this -> parse[]	= $this -> parseTemplate( "field.value.below" , 
														 array( 	"name" 	=> $element['value-b'] , 
																	"value" => $value , 
																	"info" 	=> (isset($element['info']) ? $element['info'] : false),
																	"error"	=> (isset($element['error']) ? $element['error'] : false),
																	"icon"	=> (isset($element['icon']) ? $element['icon'] : false),
																	"element" => $element
														)
			);
		} elseif( isset($element['name'])) {
			$this -> parse[]	= $this -> parseTemplate( "field.value" , 
														 array( 	"name" 	=> $element['name'] , 
																	"value" => $value , 
																	"info" 	=> (isset($element['info']) ? $element['info'] : false),
																	"error"	=> (isset($element['error']) ? $element['error'] : false),
																	"icon"	=> (isset($element['icon']) ? $element['icon'] : false),
																	"element" => $element
														)
			);
		} else {
			$this -> parse[]	= $value;
		}
		if( $element['tpl'] == "input.dropdown.selectmenu" ) {
			global $template;
			$template -> js[]	= DOMAIN_HTTPS.SYSTEM_STATIC_HOST."/js/jq-ui-selectmenu.js";
		}
		if( isset($element['wysiwyg']) ) {
			global $template;
			$template -> js[] 	= DOMAIN_HTTPS.SYSTEM_STATIC_HOST."/js/tinymce/jquery.tinymce.js";
		}
		if( $element['tpl'] == "input.radio.star-rating" ) {
			global $template;
			$template -> js[]	= DOMAIN_HTTPS.SYSTEM_STATIC_HOST."/js/jq-amplify.js";
			$template -> js[] 	= DOMAIN_HTTPS.SYSTEM_STATIC_HOST."/js/jq-star-rating.js";
		}
	}
	
	final public function parseTemplate($tpl,$opts=false) {
		global $template;
		return $template -> parseTemplate($this -> tpldir . $tpl , $opts);
	}
	final public function parse() {
		if( isset($this -> elements) && is_array($this -> elements) ) {
			foreach( $this -> elements as $name => $element ) {
				$this -> addElement( $name , $element );
			}
		}
		if( isset($this -> parse)) {
			return implode("\r\n",$this -> parse);
		} else {
			global $notification;
			$notification -> add( __CLASS__ , _("Form did not have anything to output.") , "debug" );
			return false;
		}
	}
}