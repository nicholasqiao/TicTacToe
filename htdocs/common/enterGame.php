<?php

    require_once '../../config.php';
    require_once ROOT . '/php/db/Game.php';

    session_start();

	header('Location: ../play.php?gid=' . $_SESSION['gid']);
?>