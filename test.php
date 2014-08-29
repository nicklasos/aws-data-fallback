<?php
include('vendor/autoload.php');
$config = include('src/config.php');

use Aws\DynamoDb\DynamoDbClient;
use Aws\Common\Enum\Region;
use Aws\DynamoDb\Enum\Type;
use Aws\DynamoDb\Enum\AttributeAction;
use Aws\DynamoDb\Enum\ReturnValue;

$client = DynamoDbClient::factory($config['aws']);

/*
$iterator = $client->getIterator('Query', [
    'TableName' => $config['aws']['table'],
    'KeyConditions' => [
        'restored' => [
            'AttributeValueList' => [
                ['N' => 0]
            ],
            'ComparisonOperator' => 'EQ'
        ]
    ]
]);
*/

$iterator = $client->getIterator(
    'Scan',
    [
        'TableName' => $config['aws']['table'],
        'ScanFilter' => [
            'Restored' => [
                'AttributeValueList' => [
                    [Type::NUMBER => 0]
                ],
                'ComparisonOperator' => 'EQ'
            ]
        ]
    ],
    ['limit' => 1]
);

foreach ($iterator as $item) {
    wtf($item);
}

/*
$response = $client->updateItem([
    "TableName" => 'data',
    "Key" => [
//        "restored" => [Type::NUMBER => 0],
        "id" => [Type::STRING => "5400297e799e3f601f000000"]
    ],
    "AttributeUpdates" => [
        "time" => [
            "Action" => AttributeAction::PUT,
            "Value" => [Type::NUMBER => 2]
        ]
    ],
    "ReturnValues" => ReturnValue::ALL_NEW
]);

var_dump($response);
*/
