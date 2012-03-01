<?PHP
if(!defined("HYN")) { exit; }


function _v( $input , $type=true ) {
	# regular expression to see if type is a regular expression
	$isregex		= "/\/(.*)\//i";
	if( !isset($input)) {
		return false;
	} elseif( is_bool($type) && !$type) {
		return true;
	} elseif( is_bool($type) && $type == true) {
		if( !isset($input) || $input == "" ) {
			return _("this field is required");
		}
	} elseif( $type == "int" || $type == "integer" ) {
		if( (int) $input != $input ) {
			return false;
		}
	} elseif( $type == "array" ) {
		if( !isset($input) || !is_array($input) || !count($input) ) {
			return false;
		}
	} elseif( $type == "email" ) {
		$regex			= "/(?<email>[A-Z0-9._%+-]+)[\[]?@[]]?(?<domain>([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((aero|arpa|a[cdefgilmnoqrstuwxz])|(biz|b[abdefghijmnorstvwyz])|(com|cat|coop|c[acdghiklmnorsuvxyz])|d[ejkmoz]|(edu|e[ceghrstu])|f[ijkmor]|(gov|g[abdefghilmnpqrstuwy])|h[kmnrtu]|(info|int|i[delmnoqrst])|(jobs|j[emop])|k[eghimnprwyz]|l[abcikrstuvy]|(mil|mobi|museum|m[acdghklmnopqrstuvwxyz])|(name|net|n[acefgilopruz])|(om|org)|(pro|p[aefghklmnrstwy])|qa|r[eouw]|s[abcdeghijklmnortvyz]|(travel|t[cdfghjklmnoprtvwz])|u[agkmsyz]|v[aceginu]|w[fs]|xxx|y[etu]|z[amw]))/i";
		if(!preg_match($regex,$input)) {
			return _("the given e-mail address is invalid");
		}
	} elseif( $type == "ymd" ) {
		$regex		= "/([0-9]{4})-([0-9]{2})-([0-9]{2})/i";
		if( !preg_match($regex , $input )) {
			return _("this date format is incorrect, use YYYY-mm-dd");
		}
	} elseif( $type == "ymdhis" ) {
		$regex		= "/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2}/i";
		if( !preg_match($regex , $input )) {
			return _("this date & time format is incorrect, use YYYY-mm-dd HH:ii:ss");
		}
	} elseif( $type == "password" ) {
		if( strlen( $input ) < 6 ) {
			return _("the password must be at least 6 characters long");
		}
		if( preg_match( "/^([a-z]*)$/i" , $input )) {
			return _("the password must contain not only characters");
		}
		$regex	= "/([0-9]+)/i";
		$cnt	= strlen($input);
		if( !preg_match( $regex , $input ) ) {
			return _("the password must contain at least a digit");
		}
		$regex	= "/[\W]/";
		if( !preg_match( $regex , $input ) ) {
			return _("the password must contain at least a symbol");
		}
		
	} elseif( $type == "kvk" ) {
/*		$c			= curl_init();
		curl_setopt($c,CURLOPT_URL,"http://api.openkvk.nl/php/SELECT%20*%20FROM%20kvk%20WHERE%20kvks%20=%2052038219%20LIMIT%201;");
		curl_setopt($c,CURLOPT_HEADER,0);
		curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
		$results	= curl_exec($c);
		curl_close($c);
		echo $results;
		return (count($results['ROWS']) > 0 ? true : false );
*/		
	} elseif( preg_match( $isregex,$type )) {
		if( !preg_match( $type , $input )) {
			return _("incorrect value, must be like {example}");
		}
	}
	return true;
}