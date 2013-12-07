<?php
 /* updatePass.php
  */

require_once '../../config.php';
require_once ROOT . '/php/db/User.php';

if (!isset($_REQUEST['uid']) || !isset($_REQUEST['password']) || !isset($_REQUEST['token']))
	echo 'error';

if (User::updatePass($_REQUEST['uid'], $_REQUEST['password'], $_REQUEST['token']))
	echo 'Your password has been reset. <a href="/index.html">Home</a>';
else
	echo 'An error occurred. <a href="/index.html">Home</a>';

