<?php
namespace App\Modules\Parser\Infrastructure\Persistence\Elasticsearch;

use Elasticsearch\Client;

class ElasticsearchIndexer
{
    private Client $elasticsearchClient;
    private string $indexName;

    public function __construct(Client $elasticsearchClient, string $indexName)
    {
        $this->elasticsearchClient = $elasticsearchClient;
        $this->indexName = $indexName;
    }

    public function createIndex(): void
    {
        $params = [
            'index' => $this->indexName,
            'body' => [
                'mappings' => [
                    'properties' => [
                        'name' => [
                            'type' => 'text',
                        ],
                        'link' => [
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ];

        $this->elasticsearchClient->indices()->create($params);
    }

    public function deleteIndex(): void
    {
        $params = [
            'index' => $this->indexName,
        ];

        $this->elasticsearchClient->indices()->delete($params);
    }

    public function indexDocument(array $document): void
    {
        $params = [
            'index' => $this->indexName,
            'body' => $document,
        ];

        $this->elasticsearchClient->index($params);
    }
}
