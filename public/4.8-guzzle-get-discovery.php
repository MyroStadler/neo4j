<?php

include '../include/header.php';

include '../include/guzzle-client.php';

$response = $client->get('');

include '../include/guzzle-response.php';