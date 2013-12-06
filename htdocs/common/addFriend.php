<?php
    require_once '../../config.php';
    require_once ROOT . '/php/db/User.php';
    session_start();
    if ($_REQUEST['reqid'] && $_SESSION['uid'])
    {
        $curUser = new User($_SESSION['uid']);
        $curUser->addFriend($_REQUEST['reqid']);
        $curGame = $_SESSION['gid'];
        echo '<!DOCTYPE HTML><html><head><title>Friend added!</title></head><body> Friend Added <a href="../play.php?gid=' . $curGame . '&friend=1">Go Back</a></body></html>';
    }
    else
    {
        echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body> Error Adding Friend <a href="../play.php?gid=' . $curGame . '">Go Back</a></body></html>';
    }
?>