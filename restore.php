<?php
// Run script only from command line
if (php_sapi_name() !== 'cli') {
    exit();
}

include "vendor/autoload.php";
$config = include("src/config.php");

$app = new Fallback\Application($config);

$app->restore(function ($row) {

    // save data

    return false; // if true - mark row as restored
});
