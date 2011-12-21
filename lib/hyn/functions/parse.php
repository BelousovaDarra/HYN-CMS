<?PHP

if(!defined("HYN")) { exit; }

function _p_value( $value , $type=false ) {
	global $notification;
	$r['serialized']	= "/^([a-z]{1}(.*))$/";
	$r['string']		= "/^([\d\w\s]+)$/i";
	$r['int']			= "/^([0-9]+)$/";
	$r['float']			= "/^([0-9\.,]+)$/";
	if( $type == "bool" || $type == "boolean" ) {
		if( is_bool( $value ) ) {
			return (bool) $value; }
	} elseif( $type == "empty" || $type == "null" ) {
		if( is_null($value) || $value = NULL || $value == "" ) {
			return true; }
	} elseif( $type == "string" ) {
		if( !preg_match($r[$type],$value)) {
			return (string) $value; }
	} elseif( $type == "int" ) {
		if( is_int($value) && preg_match($r['int'],$value)) {
			return (int) $value; }
	} elseif( $type == "float" ) {
		if( is_float($value) && preg_match($r['float'],$value)) {
			return (float) $value; }
	} elseif( $type == "serialized" ) {
		if( preg_match($r['serialized'],$value) && $ret = @unserialize($value)) {
			return $ret; }
	} elseif( $type == "dir" ) {
		if( is_dir( $value )) {
			return $value;
		}
	} else {
# [ TODO ]
#		if(isset($notification)) {
#			$notification -> add(__FUNCTION__,sprintf(_("Could not parse value %s of type %s."),$value,$type),"debug");
#		}
	}
	return false;
}

function _p_date( $value , $to="dmy" ) {

	# validate value first, must be YYYY-mm-dd
	if( _v( $value , "ymd" ) != TRUE && _v( $value , "ymdhis" ) != TRUE ) {
		return $value;
	}
	list($y,$m,$d)		= explode("-",$value);
	$now				= new DateTime;
	$valueDT			= new DateTime($value);
	$diff				= $now -> diff($valueDT);
	switch( $to ) {
		case "dmy":
			return sprintf("%d-%d-%d",$d,$m,$y);
			break;
		case "days":
			return $diff -> format("%r%a ". _("days"));
			break;
		case "countdown":
			if( $diff -> format("%y") > 0 ) {
				return ($diff -> format("%y") > 1 ? $diff -> format("%y ")._("years") : _("year"));
			} elseif( $diff -> format("%m") > 0 ) {
				return ($diff -> format("%m") > 1 ? $diff -> format("%m ")._("months") : _("months"));
			} elseif( $diff -> format("%d") > 0 ) {
				return ($diff -> format("%d") > 1 ? $diff -> format("%d ")._("days") : _("day"));
			}
	}
}

function _p_field_to_array( $value , $type , $delimiter="," ) {
	if( $type == "serialized" && $ret = _p_value($value , "serialized")) {
		return $ret;
	} elseif( $type == "delimited" && $ret = explode($delimiter,$value) ) {
		return $ret;
	}
	return false;
}
function _p_a( $title , $href , $rel=false , $class=false ) {
	return '<a href="'.$href.'"'.
				($class ? ' class="'.$class.'"' : false).
				($rel ? ' rel="'.$rel.'"' : false ).'>'.$title.'</a>';
}
function _p_icon( $icoclasses , $title ) {
	return "<span class=\"".$icoclasses."\" title=\"".$title."\"></span>";
}
function _p_money( $amount ) {
	if( !_v($amount,"int")) {
		return false;
	} else {
		return "&euro; " . sprintf("%01.2f",($amount / 100 ));
	}
}
function _p_bytes( $bytes , $to=false ) {
	
	$sizes		= array( _("B") , _("KB") , _("MB") , _("GB") , _("TB") , _("PB") );
	$in			= $bytes;
	$f			= false;
	$i			= 0;
	while( !$f ) {
		if( round( $in / 1024) < 1 || ($to && $to == $sizes[$i]) ) {
			return $in . " " . $sizes[$i];
			$f	= true;
		} else {
			$in 	= round( $in / 1024 );
		}
		$i++;
	}	
}
function _p_redirect( $to ) {
	header("location: ".$to);
	exit;
}
