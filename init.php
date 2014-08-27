<?php
// Run script only from command line
if (php_sapi_name() !== 'cli') {
    exit();
}

include "vendor/autoload.php";
$config = include("src/config.php");

$db = new Fallback\Dynamo($config['aws']);
$result = $db->initDB();

var_dump($result);
