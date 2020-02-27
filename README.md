# Neo4j

Disposable Neo4j installation for quick and dirty tests.

## KPIs

- setup and teardown is easy and leaves minimal artefacts
- host requirements are none to slim
- neo4j version ^4.0
- PHP version ^7.2.5
- can run queries directly on database
- can run PHP in browser that connects to database
- neo4j database and logs persist when stopping container

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

At the time of writing, contrary to the claims made by the neo4j docs, 
the pseudo-official PHP libraries do not work with neo4j 4.0. 
Therefore the method used to interact is calling the neo4j HTTP API on 
`http://neo4j:7474` using a PHP Guzzle client.

#### Example PHP: count nodes

```php
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
```
 