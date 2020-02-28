<?php

include '../include/header.php';

include '../include/graphaware-client.php';

$fakeNames = new \Myro\NameStream\Generator();
$query = 'CREATE (n:Person $props) RETURN n;';
$params = [
    'props' => [
        'name' => sprintf('%s %s %s', $fakeNames->g(), $fakeNames->g(), $fakeNames->g()),
    ],
];
$result = $client->run($query, $params);

include '../include/graphaware-result.php';
