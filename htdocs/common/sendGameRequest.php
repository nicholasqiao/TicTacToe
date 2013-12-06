<?php
    require_once '../../config.php';
    require_once ROOT . '/php/db/Game.php';
    session_start();
    if ($_REQUEST['reqid'] && $_SESSION['uid'])
    {
        $uid = $_SESSION['uid'];
        $uid2 = $_REQUEST['reqid'];
        
        $req = Game::gameRequest($uid, $uid2);
        if ($req == false)
        {
            echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body>An error has occured with the game request <a href="../userProfile.php">Go Back</a></body></html>';
        }
        else
        {
            echo '<!DOCTYPE HTML><html><head><title>Success</title></head><body>You have sent a game invite. When accepted, simply join the search queue to instantly get into the game that was accepted.<a href="../userProfile.php">Go Back</a></body></html>';
        }
    }
    else 
    {
        echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body>An error has occured with the game request <a href="../userProfile.php">Go Back</a></body></html>';
    }
?>