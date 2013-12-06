<?php
    require_once '../../config.php';
    require_once ROOT . '/php/db/User.php';
    session_start();
    if ($_REQUEST['reqid'] && $_SESSION['uid'])
    {
        $uid = $_SESSION['uid'];
        $uid2 = $_REQUEST['reqid'];
        
        $curUser = new User($uid);
        $delFriend = $curUser->delFriend($uid2);
        if ($delFriend == false)
        {
            echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body>An error has occured when removing a friend <a href="../userProfile.php">Go Back</a></body></html>';
        }
        else
        {
            echo '<!DOCTYPE HTML><html><head><title>Success</title></head><body>You have removed the friend<a href="../userProfile.php">Go Back</a></body></html>';
        }
    }
    else 
    {
        echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body>An error has when removing a friend <a href="../userProfile.php">Go Back</a></body></html>';
    }
?>