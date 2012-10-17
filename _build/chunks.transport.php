<?php
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define sources */
$root = dirname(dirname(__FILE__)) . '/';
$sources= array (
    'root' => $root,
    'build' => $root . '_build/',
    'source_core' => $root . 'core/components/currencyconverter',
    'source_assets' => $root . 'assets/components/currencyconverter',
    'data' => $root . '_build/data/',
    'docs' => $root . 'core/components/currencyconverter/docs/',
);
unset($root);

/* instantiate MODx */
require_once $sources['build'].'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(xPDO::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');


/* create category */

$category = $modx->getObject('modCategory', array('category'=>'Currency Converter'));

if (! $category) {
    $category= $modx->newObject('modCategory');
    $category->set('category','Currency Converter');
}

/* add chunks */
$modx->log(modX::LOG_LEVEL_INFO,'Adding in chunks.');
$chunks = include $sources['data'].'transport.chunks.php';

foreach($chunks as $chunk) {
    $chunk->set('id','');
}
if (is_array($chunks)) {
    $category->addMany($chunks);
} else { $modx->log(modX::LOG_LEVEL_FATAL,'Adding chunks failed.'); }

if ($category->save()) {
    $modx->log(modX::LOG_LEVEL_INFO,'Everything saved.');
}
/* create category vehicle */

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);
$modx->log(xPDO::LOG_LEVEL_INFO, "Execution time: {$totalTime}");
exit();
