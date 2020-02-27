<pre>
<?php echo $response->getStatusCode() ?>

<?php echo json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</pre>
