#!/usr/bin/php
<?PHP

$log		= "/var/log/hyn.log";

$pid		= pcntl_fork();
if( $pid == -1 ) { return 1; } elseif( $pid ) { return 0; } else { 
	
}