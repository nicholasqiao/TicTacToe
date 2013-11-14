<?php
#Eric Stanton

    #this is simply to connect to the db
    $user="user";
    $password="userpassword";
    $database="wombat";
    $server="localhost";

    $conn = mysql_connect($server,$user,$password) or die("Unable to connect to MySQL server");
    mysql_select_db($database) or die( "Unable to select database");
    
    $myquery = mysql_query("SELECT * FROM users");

    while($row = mysql_fetch_array($myquery))
    {
        if ($row['username'] == $_POST['login'] && $row['pass'] == $_POST['password'])
        {
            header('Location: http://localhost/TTT/htdocs/main.html');
            exit;
        }
    }
    
    print("Invalid Username/Password combination");
    
    mysql_close($conn);
?>