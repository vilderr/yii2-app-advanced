<?php

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use yii\db\Migration;

class m171102_090038_create_catalog_es_index extends Migration
{
    public function up()
    {
        $client = $this->getClient();

        try {
            $client->indices()->delete([
                'index' => 'products',
            ]);
        } catch (Missing404Exception $e) {
        }

        $client->indices()->create([
            'index' => 'products',
            'body'  => [
                'mappings' => [
                    'product' => [
                        'properties' => [
                            'id'     => [
                                'type' => 'integer',
                            ],
                            'name'   => [
                                'type' => 'text',
                            ],
                            'price' => [
                                'type' => 'integer',
                            ],
                            'discount' => [
                                'type' => 'integer',
                            ],
                            'updated_at' => [
                                'type' => 'integer',
                            ],
                            'categories' => [
                                'type' => 'integer',
                            ],
                            'tags' => [
                                'type' => 'integer',
                            ],
                            'values' => [
                                'type' => 'nested',
                                'properties' => [
                                    'property_id' => [
                                        'type' => 'integer'
                                    ],
                                    'value' => [
                                        'type' => 'keyword',
                                    ],
                                    'value_id' => [
                                        'type' => 'integer',
                                    ],
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function down()
    {
        try {
            $this->getClient()->indices()->delete([
                'index' => 'products',
            ]);
        } catch (Missing404Exception $e) {
        }
    }

    private function getClient()
    {
        return Yii::$container->get(Client::class);
    }
}
