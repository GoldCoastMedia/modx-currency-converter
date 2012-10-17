<?php
$chunks = array();

$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'currencyconvert',
    'description' => 'The output for each converted currency via the &tpl=`` snippet method.',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/currencyconvert.tpl'),
    'properties' => '',
),'',true,true);

$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 2,
    'name' => 'currencyupdate',
    'description' => 'The output for the last time the cached exchange rate was updated. This is called via the &updated=`1` snippet method and the chunk set via &updatetpl=`` ',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/currencyupdate.tpl'),
    'properties' => '',
),'',true,true);

return $chunks;
