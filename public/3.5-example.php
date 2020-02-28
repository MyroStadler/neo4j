<?php
# public/3.5-example.php

// no graphaware/neo4j-php-client library support for version 4.0 - see https://github.com/graphaware/neo4j-php-client/issues/166
// to use this library specify the docker image neo4j:3.5 instead of neo4j:latest for the neo4j service in docker-compose.yml

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$client = ClientBuilder::create()
    ->addConnection('bolt', 'bolt://neo4j:test@neo4j:7687')
    ->build();

$query = "MATCH (n) RETURN count(n);";
$result = $client->run($query);
?>

<pre>
<?php
/** @var \GraphAware\Bolt\Record\RecordView $record */
foreach ($result->getRecords() as $record) {
    $composite = [];
    foreach ($record->keys() as $i => $k) {
        $composite[$k] = $record->valueByIndex($i);
    }
    echo sprintf("%s\n", json_encode($composite, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
?>
</pre>
