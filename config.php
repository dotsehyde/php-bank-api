<?php
define('APP_NAME', 'Bank API');
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bank');

/* Connect to MySQL database */
$db =
    mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);