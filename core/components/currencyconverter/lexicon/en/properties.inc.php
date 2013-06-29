<?php
/**
 * Currency Converter
 *
 * Copyright (c) 2012 Gold Coast Media <dan@goldcoastmedia.co.uk>.
 *
 * This file is part of Currency Converter for MODx.
 *
 * Currency Converter is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Currency Converter is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Currency Converter for MODx; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * Default English Topic for Currency Converter for MODx.
 *
 * @package     currencyconverter
 * @author      Dan Gibbs <dan@goldcoastmedia.co.uk>
 * @copyright   Copyright (c) 2012 Gold Coast Media Ltd
 * @subpackage  lexicon
 * @language    en
 */

$_lang['prop_currencyconverter.area_desc']          = 'Currency';

// Snippet
$_lang['prop_currencyconverter.amount_desc']            = 'The amount/number to convert.';
$_lang['prop_currencyconverter.appid_desc']             = 'A registered APP ID from <a href="https://openexchangerates.org/signup/free" target="_blank">https://openexchangerates.org/signup/free</a>.';
$_lang['prop_currencyconverter.cachelifetime_desc']     = 'The duration to cache the exchange rate data in seconds.';
$_lang['prop_currencyconverter.cachename_desc']         = 'The name of the cache. This should not need changing.';
$_lang['prop_currencyconverter.decimalplaces_desc']     = 'Sets the number of decimal points.';
$_lang['prop_currencyconverter.decimalpoint_desc']      = 'Sets the separator for the decimal point.';
$_lang['prop_currencyconverter.from_desc']              = 'The currency code to convert FROM.';
$_lang['prop_currencyconverter.to_desc']                = 'A comma-seperated list of currency codes to convert TO.';
$_lang['prop_currencyconverter.method_desc']            = 'The method to use to read the remote feed. Use either curl or file_get_contents.';
$_lang['prop_currencyconverter.moneyformat_desc']       = 'Format a number as a currency string. See <a href="http://php.net/manual/en/function.money-format.php" target="_blank">money_format</a>.';
$_lang['prop_currencyconverter.output_desc']            = 'Set to 0 (or blank) to prevent the snippet outputting.';
$_lang['prop_currencyconverter.round_desc']             = 'Round fractions with \'up\' or \'down\'';
$_lang['prop_currencyconverter.thousandseparator_desc'] = 'Sets the thousands separator.';
$_lang['prop_currencyconverter.timeout_desc']           = 'The amount of seconds before timing out an API connection.';
$_lang['prop_currencyconverter.tpl_desc']               = 'The chunk to use for the output.';
$_lang['prop_currencyconverter.updated_desc']           = 'Outputs the currency exchange last update time.';
$_lang['prop_currencyconverter.updatedformat_desc']     = 'Changes the \'updated\' date format.';
$_lang['prop_currencyconverter.updatedtpl_desc']        = 'Sets the chunk used to display the last update time.';

// Table
$_lang['prop_currencyconverter.include_desc']  = 'A comma-seperated list of currency codes to include in the table. Leave blank for all.';
$_lang['prop_currencyconverter.exclude_desc']  = 'A comma-seperated list of currency codes to exclude.';
$_lang['prop_currencyconverter.tabletpl_desc'] = 'The table chunk template to use.';
$_lang['prop_currencyconverter.rowtpl_desc']   = 'The table row chunk template to use for each currency.';

// Chunks
$_lang['prop_currencyconverter.chunk_symbol_desc']   = 'The currency symbol';
$_lang['prop_currencyconverter.chunk_code_desc']     = 'The currency code (e.g. GBP, USD, EUR)';
$_lang['prop_currencyconverter.chunk_currency_desc'] = 'The currency string (e.g. United States Dollar)';
$_lang['prop_currencyconverter.chunk_value_desc']    = 'The converted value';

$_lang['prop_currencyconverter.chunk_updated_desc']  = 'The output for the last time the cached exchange rate was updated.';

$_lang['prop_currencyconverter.chunk_currencytabledata_desc']    = 'Table rows placeholder';


