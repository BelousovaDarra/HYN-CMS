<?PHP
if(!defined("HYN")) { exit; }

require_once "lib" . DS . "min" . DS . "lib" . DS . "Minify.php";

set_include_path( __DIR__ . DS . "lib" . DS . "min" . DS . "lib" . DS );

Minify::setCache( "/tmp" );