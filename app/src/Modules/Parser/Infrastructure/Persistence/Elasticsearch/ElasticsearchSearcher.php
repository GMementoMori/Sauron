<?php
namespace App\Modules\Parser\Infrastructure\Persistence\Elasticsearch;

use Elasticsearch\Client;

class ElasticsearchSearcher
{
    private Client $elasticsearchClient;
    private string $indexName;

    public function __construct(Client $elasticsearchClient, string $indexName)
    {
        $this->elasticsearchClient = $elasticsearchClient;
        $this->indexName = $indexName;
    }

    public function search(string $query): array
    {
        $params = [
            'index' => $this->indexName,
            'body' => [
                'query' => [
                    'match' => [
                        'name' => $query,
                    ],
                ],
            ],
        ];

        $response = $this->elasticsearchClient->search($params);

        return $response['hits']['hits'];
    }
}
