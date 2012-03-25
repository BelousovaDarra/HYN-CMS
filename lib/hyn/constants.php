<?PHP
if( !defined("HYN")) { exit; }


define(		"DS"				, DIRECTORY_SEPARATOR );
define(		"HYN_PATH_ROOT"		, dirname(dirname( __DIR__ )) . DS );
define(		"HYN_PATH_DOMAINS"	, HYN_PATH_ROOT . "domains" . DS );
#define(		"HYN_PATH_DOMAINS"	, dirname( HYN_PATH_ROOT ) . DS );
define(		"HYN_PATH_LIB"		, HYN_PATH_ROOT . "lib" . DS );

define(		"HYN_PATH_PUBLIC"	, HYN_PATH_ROOT . "public_html" . DS );
define(		"HYN_PATH_PUBLIC_JS", HYN_PATH_PUBLIC . "js" . DS );
define(		"HYN_PATH_PUBLIC_CSS", HYN_PATH_PUBLIC . "css" . DS );
define(		"HYN_PATH_PUBLIC_IMG", HYN_PATH_PUBLIC . "images" . DS );

define(		"HYN_PATH_CACHE"	, HYN_PATH_PUBLIC . "cache" . DS );
define(		"HYN_PATH_TPL"		, HYN_PATH_ROOT . "templates" . DS );

define(		"HYN_PATH_ANEWT"	, HYN_PATH_LIB . "anewt" . DS );
define(		"HYN_PATH_HYN"		, HYN_PATH_LIB . "hyn" . DS );
define(		"HYN_PATH_MODULES"	, HYN_PATH_LIB . "modules" . DS );


define(		"HYN_URI_REQUEST"	, $_SERVER['REQUEST_URI'] );
define(		"HYN_URI_HTTPS"		, (isset($_SERVER['HTTPS']) ? "https://" : "http://" ));
define(		"HYN_URI_HTTPHOST"	, $_SERVER['HTTP_HOST'] );