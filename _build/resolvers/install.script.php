<?php
$modx =& $object->xpdo;
$category = 'Currency Converter';

/* set to TRUE to connect property sets to elements */
$connectPropertySets = TRUE;

$success = TRUE;

$modx->log(xPDO::LOG_LEVEL_INFO, 'Running PHP Resolver.');
switch($options[xPDOTransport::PACKAGE_ACTION]) {
	/* This code will execute during an install */
	case xPDOTransport::ACTION_INSTALL:
		$apikey = $modx->getOption('wwoapikey', $options);
		$modx->log(xPDO::LOG_LEVEL_INFO, 'Setting APP ID');
		
		if($setting = $modx->getObject('modSystemSetting', 'currencyconverter.appid'))
		{
			if(!empty($apikey))
			{
				$setting->set('value', $apikey);
				$setting->save();
			}
		}
		else
		{
			$setting = $modx->newObject('modSystemSetting');
			$setting->set('key', 'currencyconverter.appid');
			$setting->set('description', 'setting_currencyconverter.appid_desc');
			$setting->set('value', $apikey);
			$setting->set('namespace', 'currencyconverter');
			$setting->set('area', 'API');
			$setting->set('xtype', 'text-password');
			$setting->save();
		}
		
		
		break;

	/* This code will execute during an upgrade */
	case xPDOTransport::ACTION_UPGRADE:

		/* put any upgrade tasks (if any) here such as removing
		   obsolete files, settings, elements, resources, etc.
		*/

		$success = TRUE;
		break;

	/* This code will execute during an uninstall */
	case xPDOTransport::ACTION_UNINSTALL:
		$modx->log(xPDO::LOG_LEVEL_INFO,'Uninstalling . . .');
		$success = TRUE;
		break;

}
$modx->log(xPDO::LOG_LEVEL_INFO, 'Script resolver actions completed. Traversing the space-time continuum.');
return $success;
