<?php

include '../include/header.php';

include '../include/graphaware-client.php';

$query = "MATCH (n) RETURN count(n);";
$result = $client->run($query);

include '../include/graphaware-result.php';
