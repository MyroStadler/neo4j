<?php

use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'http://neo4j:7474',
    'headers' => [
        'Authorization' => 'Basic ' . base64_encode("neo4j:test"),
        'Accept' => 'application/json',
    ],
]);
