<?php
$modx =& $object->xpdo;


$modx->log(xPDO::LOG_LEVEL_INFO, 'Checking requirements.');
switch($options[xPDOTransport::PACKAGE_ACTION]) {
	
	case xPDOTransport::ACTION_INSTALL:
		$modx->log(xPDO::LOG_LEVEL_INFO, 'Checking PHP version');
		$success = true;

		// Check PHP 5.3+
		if (version_compare(PHP_VERSION, '5.2.0', '<')) {
			$modx->log(xPDO::LOG_LEVEL_ERROR, 'This package currently requires at least PHP 5.2.');
			$success = false;
		}
		// Check cURL
		if (!in_array('curl', get_loaded_extensions())) {
			$modx->log(xPDO::LOG_LEVEL_ERROR, '** Warning: It looks like you do not have cURL. Please use &method=`file_get_contents` in your snippet calls! **');
		}
        	break;

	case xPDOTransport::ACTION_UPGRADE:
		$success = true;
		break;

	case xPDOTransport::ACTION_UNINSTALL:
		$success = true;
		break;
}

return $success;
