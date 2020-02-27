<?php

include '../include/header.php';

include '../include/guzzle-client.php';

$response = $client->post('db/neo4j/tx', [
    'json' => [
        'statements' => [
            [
                'statement' => 'MATCH (n) RETURN count(n)',
            ],
        ],
    ],
]);

include '../include/guzzle-response.php';