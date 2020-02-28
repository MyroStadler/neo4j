# Neo4j

Disposable Neo4j installation for quick and dirty tests.

## KPIs

1. setup and teardown is easy and leaves minimal artefacts
2. host requirements are none to slim
3. neo4j version ^4.0
4. PHP version ^7.2.5
5. can run queries directly on database
6. can run PHP in browser that connects to database
7. neo4j database and logs persist when stopping container

## KPI tracking

#### 3. neo4j version ^4.0

Version 4.0 introduces a new HTTP API instead of the REST API. The `graphaware/neo4j-php-client` 
composer library does not yet support the new HTTP API. To use this library we must 
downgrade the docker container to `neo4j:3.5` from `neo4j:latest`.

## Requirements

1. docker, docker-compose
1. docker containers made reachable in browser, e.g. on localhost if using docker desktop or via host file
1. make

## Installation

1. Run `make` and work through the red messages (if any)
1. http://localhost.docker/ (or wherever you have made docker reachable)

## Usage

### A note about persistance

Volumes are used to persist the neo4j logs and database. Logs are just a plain mounted directory, 
but data had to be a named volume since write permissions were a problem. See 

To refresh the database, stop and remove the neo4j_neo4j_1 ("neo4j") container and delete the `neo4j_data` volume, 
then run `make` again.

### Running database queries

1. On the host, open an interactive bash session in the neo4j docker container:

        docker-compose exec neo4j bash
1. In the neo4j docker container, open an interactive cypher query shell:

        cypher-shell -u neo4j -p test

1. In the cypher query shell, run your queries. Queries should end with a semicolon and you can exit the 
interactive shell with `:exit`

#### Trivial example queries

- Create a node:  
`CREATE (n {name: 'Joe Bloggs'}) RETURN n;`
- Count nodes:  
`MATCH (n) RETURN count(n);`
- Create a Person (node with label Person):  
`CREATE (n:Person {name: 'Mary Bloggs'}) RETURN n;`
- Count Persons:  
`MATCH (n:Person) RETURN count(n);`
- List Persons:  
`MATCH (n:Person) RETURN (n);`

### Running queries from PHP

At the time of writing the pseudo-official PHP libraries do not work with neo4j ^4.0. 

Therefore the method used to interact for version ^4.0 is calling the neo4j HTTP API on 
`http://neo4j:7474` using a PHP Guzzle client and the method used to interact with version ^3.5 
is the `graphaware/neo4j-php-client` composer library. 

Version switching is as simple as changing the tag for the neo4j service in `docker-compose.yml`. 
See Docker hub official neo4j image page: https://hub.docker.com/_/neo4j

#### Version ^4.0 example PHP: count nodes

```php
<?php
# public/4.8-example.php

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
```

#### Version ^3.5 example PHP: count nodes

```php
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
```
