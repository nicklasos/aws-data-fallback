<?php
namespace Fallback;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Enum\Type;
use Aws\DynamoDb\Enum\AttributeAction;
//use Aws\DynamoDb\Enum\ReturnValue;

class Dynamo
{
    private $client;
    private $table;

    public function __construct($config)
    {
        $this->client = DynamoDbClient::factory($config);
        $this->table = $config['table'];
    }

    public function save($data)
    {
        try {
            $this->client->putItem([
                'TableName' => $this->table,
                'Item' => $this->client->formatAttributes($data)
            ]);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getStats()
    {
        $result = $this->client->describeTable([
            'TableName' => $this->table
        ]);

        return [
            'Items' => $result['Table']['ItemCount']
        ];
    }

    public function scanNotRestored()
    {
        return $this->client->getIterator(
            'Scan',
            [
                'TableName' => $this->table,
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
    }

    public function delete($item)
    {
        $this->client->deleteItem(array(
            'TableName' => $this->table,
            'Key' => [
                'Id'   => [Type::STRING => $item['Id']['S']],
            ]
        ));
    }

    public function restore($item)
    {
        $this->client->updateItem([
            "TableName" => $this->table,
            "Key" => [
                "Id" => [Type::STRING => $item["Id"]["S"]]
            ],
            "AttributeUpdates" => [
                "Restored" => [
                    "Action" => AttributeAction::PUT,
                    "Value" => [Type::NUMBER => 1]
                ]
            ],
            // "ReturnValues" => ReturnValue::ALL_NEW
        ]);
    }
}