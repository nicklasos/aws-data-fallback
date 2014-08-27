<?php
include('vendor/autoload.php');
$config = include('src/config.php');

use Aws\DynamoDb\DynamoDbClient;

$client = DynamoDbClient::factory($config['aws']);

$result = $client->describeTable([
    'TableName' => 'data'
]);

wtf($result['Table']);

$debug = 1;

// Each item will contain the attributes we added
//foreach ($iterator as $item) {
//    wtf($item);
//}
