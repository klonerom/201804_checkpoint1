<?php

define('DSN', 'mysql:host=localhost;dbname=DBNAME');
define('USER', 'USER_NAME');
define('PASS', 'PASSWORD');

$pdo = new PDO(DSN, USER, PASS);

if (!$pdo) {
    die('error');
}
