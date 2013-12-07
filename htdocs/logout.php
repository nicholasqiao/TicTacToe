<?php

session_start();
session_destroy();

echo '<!DOCTYPE HTML><html><head><title>Logout</title></head>
      <body><p>You have been logged out. <a href="/index.html">Return</a></p>
      </body></html>';

?>
