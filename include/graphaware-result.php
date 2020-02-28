<pre>
<?php
function outnode(\GraphAware\Bolt\Result\Type\Node $v) {
    return $v->values();
}
function outval($v) {
    if (is_object($v)) {
        switch (get_class($v)) {
            case \GraphAware\Bolt\Result\Type\Node::class:
               return outnode($v);
        }
    }
    return $v;
}
/** @var \GraphAware\Bolt\Record\RecordView $record */
foreach ($result->getRecords() as $record) {
    $composite = [];
    foreach ($record->keys() as $i => $k) {
        $value = $record->valueByIndex($i);
        $composite[$k] = outval($value);
    }
    echo sprintf("%s\n", json_encode($composite, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
?>
</pre>
