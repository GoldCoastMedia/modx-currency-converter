<?php
/* This section is ONLY for new System Settings to be added to
 * The System Settings grid. If you include existing settings,
 * they will be removed on uninstall. Existing setting can be
 * set in a script resolver (see install.script.php).
 */
$settings = array();

$settings['setting_currencyconverter.appid']= $modx->newObject('modSystemSetting');
$settings['setting_currencyconverter.appid']->fromArray(array (
	'key' => 'currencyconverter.appid',
	'description' => 'setting_currencyconverter.appid_desc',
	'value' => '',
	'namespace' => 'currencyconverter',
	'area' => 'API',
	'xtype' => 'text-password',
), '', true, true);

$settings['setting_currencyconverter.cachelifetime']= $modx->newObject('modSystemSetting');
$settings['setting_currencyconverter.cachelifetime']->fromArray(array (
	'key' => 'currencyconverter.cachelifetime',
	'description' => 'setting_currencyconverter.cachelifetime_desc',
	'value' => '14400',
	'namespace' => 'currencyconverter',
	'area' => 'Caching',
	'xtype' => 'textfield',
), '', true, true);

$settings['setting_currencyconverter.defaultcurrency']= $modx->newObject('modSystemSetting');
$settings['setting_currencyconverter.defaultcurrency']->fromArray(array (
	'key' => 'currencyconverter.defaultcurrency',
	'description' => 'setting_currencyconverter.defaultcurrency_desc',
	'value' => 'USD',
	'namespace' => 'currencyconverter',
	'area' => 'API',
	'xtype' => 'textfield',
), '', true, true);

$settings['setting_currencyconverter.timeout']= $modx->newObject('modSystemSetting');
$settings['setting_currencyconverter.timeout']->fromArray(array (
	'key' => 'currencyconverter.timeout',
	'description' => 'setting_currencyconverter.timeout_desc',
	'value' => 5,
	'namespace' => 'currencyconverter',
	'area' => 'API',
	'xtype' => 'textfield',
), '', true, true);

return $settings;
