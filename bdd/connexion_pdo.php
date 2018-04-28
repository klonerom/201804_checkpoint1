<?php

define('DSN', 'mysql:host=localhost;dbname=checkpoint1');
define('USER', 'rom1');
define('PASS', 'wcsrom1');

$pdo = new PDO(DSN, USER, PASS);

if (!$pdo) {
    die('error');
}
