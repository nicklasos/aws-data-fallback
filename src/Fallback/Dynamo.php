<?php
namespace Fallback;

use Aws\DynamoDb\DynamoDbClient;

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

    public function initDB()
    {
        return $this->client->createTable([
            'TableName' => $this->table,
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S'
                ],
                [
                    'AttributeName' => 'time',
                    'AttributeType' => 'N'
                ],
                [
                    'AttributeName' => 'restored',
                    'AttributeType' => 'N'
                ]
            ],
            'KeySchema' => [
                [
                    'AttributeName' => 'id',
                    'KeyType'       => 'HASH'
                ],
                [
                    'AttributeName' => 'time',
                    'KeyType'       => 'RANGE'
                ]
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits'  => 10,
                'WriteCapacityUnits' => 20
            ]
        ]);
    }
}