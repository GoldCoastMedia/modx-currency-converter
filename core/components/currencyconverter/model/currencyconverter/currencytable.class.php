<?php
/**
 * Currency Table
 *
 * Copyright (c) 2012 Gold Coast Media Ltd
 *
 * This file is part of Currency Converter for MODx.
 *
 * Currency Converter is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Currency Converter is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Currency Converter if not, write to the Free Software Foundation, Inc., 59 
 * Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package  currencyconverter
 * @author   Dan Gibbs <dan@goldcoastmedia.co.uk>
 */
 
class CurrencyTable extends CurrencyConverter {

	public $config = array(
		'amount'            => 1,
		'decimalplaces'     => 2,
		'decimalpoint'      => '.',
		'from'              => 'USD',
		'include'           => NULL,
		'exclude'           => NULL,
		'moneyformat'       => FALSE,
		'round'             => FALSE,
		'thousandseparator' => ',',
		'tpl'               => 'currencytable',
		'rowtpl'            => 'currencytablerow',
	);
	
	protected $modx = NULL;
	protected $converter = NULL;
	
	public function __construct(modX &$modx, array &$config, $converter)
	{
		$this->modx =& $modx;
		$this->modx->lexicon->load('currencyconverter:default');
		$this->converter = $converter;
		
		// Force all parameters to lowercase
		$config = array_change_key_case($config, CASE_LOWER);
		
		
		// Get MODx Manager settings
		$settings = $this->modx->newQuery('modSystemSetting')->where(
			array('key:LIKE' => $this->namespace . '%')
		);
		$settings = $this->modx->getCollection('modSystemSetting', $settings);

		// Apply MODx manager settings
		foreach($settings as $key => $setting) {
			$key = str_replace($this->namespace, '', $key);

			// Don't overwrite snippet params
			if(empty($config[$key]) OR $config[$key] === NULL)
				$config[$key] = $setting->get('value');
		}

		// Merge snippet parameters and system settings with default config
		$this->config = array_merge($this->config, $config);
		
		// Merge formatting options
		$this->converter->config = array_merge($this->converter->config, array(
			'decimalplaces'     => $this->config['decimalplaces'],
			'decimalpoint'      => $this->config['decimalpoint'],
			'moneyformat'       => $this->config['moneyformat'],
			'round'             => $this->config['round'],
			'thousandseparator' => $this->config['thousandseparator'],
		));
	}
	
	public function run()
	{
		// FIXME: Repetitive 
		// Get a list of currencies to use
		$feed = $this->converter->fetch_feed();
		
		if($this->converter->valid_feed($feed)) {
			$feed = json_decode($feed);
			
			// Get the currencies requested
			$currencies = $this->currencies($feed, $this->config['include'], $this->config['exclude']);
			
			// Get the exchange rates for each currency
			$exchange = $this->exchange($this->config['amount'], $this->config['from'], $currencies, $feed);
			
			// Row placeholder
			$rows = $this->chunk_rows($this->config['rowtpl'], $exchange);
			$table = $this->converter->get_chunk($this->config['tpl'], array('currencytabledata' => $rows) );
			
			return $table;
		}
		else {
			return;
		}
	}

	/**
	 *
	 *
	 */
	protected function exchange($amount = 1, $from = 'USD', $currencies, $feed)
	{
		foreach($currencies as $symbol => $currency) {
			
			$value = $this->converter->exchange($amount, $from, $symbol, $feed);
			$value = $this->converter->format($value);
	
			$currencies->{$symbol} = $value;
		}
		
		return $currencies;
	}

	/**
	 *
	 *
	 */
	protected function currencies($feed, $include, $exclude)
	{
		$currencies = new StdClass();
		
		$include = array_filter(array_map('strtoupper', $this->converter->prepare_array($include) ));
		$exclude = array_filter(array_map('strtoupper', $this->converter->prepare_array($exclude) ));
		
		// If empty include everthing
		if( empty($include) ) {
			$currencies = $feed->rates;
		}
		// Include list
		else {
			$currencies = $this->currency_list($feed, $currencies, 'include', $include);
		}
		
		// Exclude
		if( !empty($exclude) ) {
			$currencies = $this->currency_list($feed, $currencies, 'exclude', $exclude);
		}
		
		return $currencies;
	}

	/**
	 *
	 *
	 */
	protected function currency_list($feed, $list = array(), $type = NULL, $symbols = array())
	{
		foreach($symbols as $symbol) {
			if(property_exists($feed->rates, $symbol)) {
			
				if($type == 'include') 
					$list->{$symbol} = $feed->rates->{$symbol};
					
				elseif($type == 'exclude')
					unset($list->{$symbol});
			}
		}
		
		return $list;
	}
	
	protected function chunk_rows($tpl, $rows)
	{
		$output = NULL;
		
		foreach($rows as $code => $value) {
			
			$name = $this->modx->lexicon('currencyconverter.string_' . $code);
			$symbol = $this->modx->lexicon('currencyconverter.symbol_' . $code);
			
			$properties = array(
				'currency' => $name,
				'symbol'   => $symbol,
				'value'    => $value,
				'code'     => $code,
			);
			
			$output .= $this->converter->get_chunk($tpl, $properties);
		}
		
		return $output;
	}

}
