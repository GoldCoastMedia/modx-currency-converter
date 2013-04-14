<?php
if (! function_exists('getSnippetContent')) {
    function getSnippetContent($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<?php','',$o);
        $o = str_replace('?>','',$o);
        $o = trim($o);
        return $o;
    }
}
$snippets = array();

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'CurrencyConverter',
    'description' => 'CurrencyConverter snippet',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/currencyconverter.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'/properties/properties.currencyconverter.php';
$snippets[1]->setProperties($properties);
unset($properties);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'CurrencyTable',
    'description' => 'CurrencyTable generator snippet',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/currencytable.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'/properties/properties.currencytable.php';
$snippets[2]->setProperties($properties);
unset($properties);
return $snippets;
