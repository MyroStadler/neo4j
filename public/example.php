<?php
# public/example.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';


use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'http://neo4j:7474',
    'headers' => [
        'Authorization' => 'Basic ' . base64_encode("neo4j:test"),
        'Accept' => 'application/json',
    ],
]);

$response = $client->post('db/neo4j/tx', [
    'json' => [
        'statements' => [
            [
                'statement' => 'MATCH (n) RETURN count(n)',
            ],
        ],
    ],
]);
?>

<pre>
<?php echo $response->getStatusCode() ?>

<?php echo json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</pre>