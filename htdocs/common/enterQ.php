<?php

    require_once '../../config.php';
    require_once ROOT . '/php/db/Game.php';

    session_start();
    $uid = $_SESSION['uid'];
    
    $_SESSION['gid'] = 1;
    header('Location: ../home.html');
    
    /*
    $inq = Game::enQ($uid);
    $qSize = Game::sizeofQ();

    //$inq = Game::enQ($uid);
    $qSize = Game::sizeofQ();
    
    $newUid = Game::deQ();
    echo ($qSize);
    echo ($newUid);

    if ($qSize == 0)
    {
        header('Location: ../searching.html');
    }
    else
    {
        $firstPlayer = Game::deQ();
        $gameState = "000000000";
        $newGameId = Game::newGame($uid, $firstPlayer, $gameState);
        
        if ($newGameId == -1)
        {
            echo '<!DOCTYPE HTML><html><head><title>Error</title></head><body>An error has occured, you are not in the queue <a href="../main.html">Go Back</a></body></html>';
        }
        else
        {
            $_SESSION['gid'] = $newGameId;
        }
        Game::deQ();//Removes this players in the Q
	header('Location: ../home.html');
    }
    */
?>