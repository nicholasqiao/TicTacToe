<?php
/*
 *  Top-level php file. Include this once so that you can use the variable ROOT
 * to access all other includes. This way, when you port the site to a new
 * system, you only have to change this once.
 *
 *  Pretty much every PHP file should include this as it also sets up error
 * logging.
 *
 *  Notice there is no slash at the end. This should pretty much always be the
 * same as your ServerRoot in httpd.conf.
 */

date_default_timezone_set('America/New_York');
 
define('ROOT','C:/wamp/www/TTT/');
define('ERR', ROOT.'/error.log');

function error($str) {
	$err = "[" . date("r") . "]: " . $str . "\n";
	error_log($err,3,ERR);
} 


?>
