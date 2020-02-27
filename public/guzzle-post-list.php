<?php

include '../include/header.php';

include '../include/guzzle-client.php';

$nameStream = new \Myro\NameStream\Generator();

$response = $client->post('db/neo4j/tx', [
    'json' => [
        'statements' => [
            [
                'statement' => 'MATCH (n:Person) RETURN (n)',
            ],
        ],
    ],
]);

include '../include/guzzle-response.php';