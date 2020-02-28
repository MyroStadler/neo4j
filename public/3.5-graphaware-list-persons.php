<?php

include '../include/header.php';

include '../include/graphaware-client.php';

$fakeNames = new \Myro\NameStream\Generator();
$query = 'MATCH (n:Person) RETURN (n);';
$result = $client->run($query);

include '../include/graphaware-result.php';
