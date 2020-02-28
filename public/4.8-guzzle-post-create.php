<?php

include '../include/header.php';

include '../include/guzzle-client.php';

$nameStream = new \Myro\NameStream\Generator();

$response = $client->post('db/neo4j/tx/commit', [
    'json' => [
        'statements' => [
            [
                'statement' => 'CREATE (n:Person $props) RETURN n',
                'parameters' => [
                    'props' => [
                        'name' => sprintf('%s %s %s', $nameStream->generate(), $nameStream->generate(), $nameStream->generate()),
                    ],
                ],
            ],
        ],
    ],
]);

include '../include/guzzle-response.php';