<?php
// Run script only from command line
if (php_sapi_name() !== 'cli') {
    exit();
}

include "vendor/autoload.php";
$config = include("src/config.php");

use Aws\DynamoDb\DynamoDbClient;

$client = DynamoDbClient::factory($config['aws']);
$table = $config['aws']['table'];

$result = $client->createTable([
    'TableName' => $table,
    'AttributeDefinitions' => [
        [
            'AttributeName' => 'Id',
            'AttributeType' => 'S'
        ]
    ],
    'KeySchema' => [
        [
            'AttributeName' => 'Id',
            'KeyType'       => 'HASH'
        ]
    ],
    'ProvisionedThroughput' => [
        'ReadCapacityUnits'  => 10,
        'WriteCapacityUnits' => 20
    ]
]);

var_dump($result);
