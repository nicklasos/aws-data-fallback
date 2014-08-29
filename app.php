<?php
include "vendor/autoload.php";
$config = include("src/config.php");

$app = new Fallback\Application($config);

$app->onError(function () {
    return 'error';
});

$app->onSuccess(function () {
    return 'success';
});

$app->on404(function () {
    return '404';
});

$app->getId(function () {
    return new MongoId();
});

$app->getContent(function () {
    $data = file_get_contents('php://input');

    return [
        'Json' => $data,
        'UserId' => $_GET['id']
    ];
});

$app->run();
