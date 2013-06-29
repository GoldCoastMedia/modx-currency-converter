<?php
/**
 * Currency Table
 *
 * Copyright (c) 2012 Gold Coast Media Ltd
 *
 * This file is part of Currency Convertor for MODx.
 *
 * Currency Convertor is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Currency Convertor is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Currency Convertor; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package currencyconverter
 * @author  Dan Gibbs <dan@goldcoastmedia.co.uk>
 */

require_once $modx->getOption('core_path') . 'components/currencyconverter/model/currencyconverter/currencyconverter.class.php';
require_once $modx->getOption('core_path') . 'components/currencyconverter/model/currencyconverter/currencytable.class.php';

$cc_opts = array('output' => FALSE);

$cc = new CurrencyConverter($modx, $cc_opts);
$ct = new CurrencyTable($modx, $scriptProperties, $cc);

$result = $ct->run();
unset($ct, $cc);

return $result;

