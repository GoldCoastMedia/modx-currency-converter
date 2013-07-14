<?php
/**
 * Currency Converter
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

class CurrencyConverter {

	public $config = array(
		'amount'            => 1,
		'appid'             => NULL,
		'cachelifetime'     => 1800,
		'cachename'         => 'openexchangerates',
		'decimalplaces'     => 2,
		'decimalpoint'      => '.',
		'from'              => 'USD',
		'to'                => 'EUR',
		'method'            => 'curl',
		'moneyformat'       => FALSE,
		'output'            => TRUE,
		'round'             => FALSE,
		'thousandseparator' => ',',
		'timeout'           => 5,
		'tpl'               => 'currencyconvert',
		'updated'           => FALSE,
		'updatedformat'     => 'l jS F g:ia',
		'updatedtpl'        => 'currencyupdate',
	);

	// MODx caching options
	public $cache_opts = array(
		xPDO::OPT_CACHE_KEY => 'includes/elements/currencyconverter',
	);

	protected $modx      = NULL;
	protected $namespace = 'currencyconverter.';
	protected $api_url   = 'http://openexchangerates.org/api/latest.json?';

	public function __construct(modX &$modx, array &$config)
	{
		$this->modx =& $modx;
		//$this->modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
		$this->modx->lexicon->load('currencyconverter:default');

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
		
		// Enable debugging
		if($this->config['debug'])
			$this->modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
	}

	public function run() 
	{
		$feed = $this->fetch_feed();

		if($this->valid_feed($feed) === FALSE)
		{
			$error = $this->modx->lexicon('currencyconverter.error_feed_failed');
			$this->modx->log(modX::LOG_LEVEL_DEBUG, $error);
		}
		else
		{
			$output = NULL;
			$feed = json_decode($feed);

			// Convert
			if($this->config['output'] AND $this->config['amount'] > 0 AND $this->config['to'] !== NULL)
			{
				$output .= $this->convert(
					$this->config['amount'],
					$this->config['from'],
					$this->config['to'],
					$feed
				);
			}

			// Get last update
			if($this->config['updated'] !== FALSE)
			{
				$updated = date($this->config['updatedformat'], $feed->timestamp);
				$output .= $this->get_chunk($this->config['updatedtpl'], array('updated' => $updated) );
			}

			return $output;
		}
	}

	/**
	 * Fetches the feed
	 *
	 * @return  feed
	 */
	protected function fetch_feed()
	{
		$url = $this->build_request_uri();
		$feed = $this->feed_cache($this->config['cachename'], $this->config['cachelifetime'], $url);
		
		return $feed;
	}
	
	/**
	 * Convert
	 *
	 * @param   int     $amount      the amount to convert
	 * @param   string  $from        currency code to convert from
	 * @param   array   $currencies  comma separated string of currency codes
	 * @param   object  $feed        decoded JSON feed object of rates
	 * @return  NULL|string
	 */
	protected function convert($amount = 0, $from = 'USD', $currencies, $feed)
	{
		$output = NULL;

		// Make an array from CSV list of currencies
		$currencies = $this->prepare_array($currencies);

		foreach($currencies as $id => $currency)
		{
			// Currency codes must be uppercase
			$from = strtoupper($from);
			$currency = strtoupper($currency);

			// Check the currency code is available
			if(property_exists($feed->rates, $currency))
			{
				$name = $this->modx->lexicon('currencyconverter.string_' . $currency);
				$symbol = $this->modx->lexicon('currencyconverter.symbol_' . $currency);
				$value = $this->exchange($amount, $from, $currency, $feed);
				$value = $this->format($value);

				$properties = array(
					'currency' => $name,
					'symbol'   => $symbol,
					'value'    => $value,
					'code'     => $currency,
				);

				$output .= $this->get_chunk($this->config['tpl'], $properties);
			}
			else
			{
				$error = $this->modx->lexicon('currencyconverter.error_unknown_code', array('code' => $currency) );
				$this->modx->log(modX::LOG_LEVEL_DEBUG, $error);
			}
		}

		return $output;
	}

	/**
	 * Currency exchange
	 *
	 * @param   int     $amount  the amount to convert
	 * @param   string  $from    currency code to convert from
	 * @param   string  $to      currency code to convert to
	 * @param   object  $feed    decoded JSON feed object of rates
	 * @return  int
	 */
	protected function exchange($amount = 0, $from = 'USD', $to, $feed)
	{
		$exchange = ( ($amount / $feed->rates->$from) * $feed->rates->$to );
		return $exchange;
	}

	/**
	 * Format the returned currency number
	 *
	 * @param   string|int|float  $value  the number/value to format
	 * @return  string|int|float
	 */
	protected function format($value = 0)
	{
		// Rounding
		if( strtolower($this->config['round']) === 'up')
			$value = ( is_float($value) ) ? ceil($value) : $value;
		elseif( strtolower($this->config['round']) === 'down')
			$value = ( is_float($value) ) ? floor($value) : $value;

		// Formatting
		if( !empty($this->config['moneyformat']) )
		{
			$value = money_format($this->config['moneyformat'], $value);
		}
		else
		{
			$value = number_format(
				$value,
				$this->config['decimalplaces'],
				$this->config['decimalpoint'],
				$this->config['thousandseparator']
			);
		}

		return $value;
	}

	/**
	 * Check and return if the feeds JSON is valid
	 *
	 * @param   string  $feed  the JSON feed
	 * @return  bool
	 */
	protected function valid_feed($feed)
	{
		$json_feed = json_decode($feed);

		if(function_exists('json_last_error'))
		{
			if(json_last_error() !== JSON_ERROR_NONE)
				$json_feed = NULL;
		}

		if($feed === NULL OR $json_feed === NULL)
		{
			$error = $this->modx->lexicon('currencyconverter.error_parsing_feed');
			$this->modx->log(modX::LOG_LEVEL_DEBUG, $error);
			return FALSE;
		}
		else
		{
			// Check for feed based errors
			if(property_exists($json_feed, 'error'))
			{
				$error = $this->modx->lexicon('currencyconverter.error_api', array(
					'status'      => $json_feed->status,
					'message'     => $json_feed->message,
					'description' => $json_feed->description,
				));

				$this->modx->log(modX::LOG_LEVEL_DEBUG, $error);

				return FALSE;
			}
			else
				return TRUE;
		}
	}

	/**
	 * Fetch from or cache or create new request
	 *
	 * @param   string  $name    cache name
	 * @param   string  $life    cache lifetime
	 * @param   string  $url     feed URL
	 * @return  string
	 */
	protected function feed_cache($name, $life, $url)
	{
		if($life > 0)
		{
			if(!$cached = $this->modx->cacheManager->get($name, $this->cache_opts))
			{
				$cached = $this->get_feed($url, $this->config['method'], $this->config['timeout']);

				// Only cache valid feeds
				if($this->valid_feed($cached) AND $cached !== NULL)
					$this->modx->cacheManager->set($name, $cached, $life, $this->cache_opts);
			}

			return $cached;
		}
		else
			return $this->get_feed($url, $this->config['method'], $this->config['timeout']);
	}

	/**
	 * Build the request URL
	 *
	 * @return  string|bool
	 */
	protected function build_request_uri()
	{
		$url_params = array(
			'app_id' => $this->config['appid'],
		);

		$url = $this->api_url . http_build_query($url_params);

		return $url;
	}

	/**
	 * Get the exchange rate
	 *
	 * @param   string  $url     The URL
	 * @param   string  $method  The method used to fetch the feed
	 * @return  bool
	 */
	protected function get_feed($url = NULL, $method = NULL, $timeout = 5)
	{
		if( $url !== NULL )
		{
			$method = strtolower('fetch_' . $method);
			return $this->$method($url, $timeout);
		}
		else
		{
			$error = $this->modx->lexicon('currencyconverter.error_fetch_feed', array('url', $url) );
			$this->modx->log(modX::LOG_LEVEL_DEBUG, $error);
			return FALSE;
		}
	}

	/**
	* Fetch feed via cURL.
	*
	* @param   string  $url
	* @param   int     $timeout
	* @return  string  Returns JSON
	*/
	protected function fetch_curl($url, $timeout = 5)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$feed = curl_exec($ch);
		curl_close($ch);

		return $feed;
	}

	/**
	* Returns remote feed via file_get_contents function.
	*
	* @param   string  $url
	* @param   int     $timeout
	* @return  string  Returns JSON
	*/
	protected function fetch_file_get_contents($url, $timeout = 5)
	{
		$sc = stream_context_create( array('http' => array('timeout' => (int) $timeout)) );
		$feed = file_get_contents($url, FALSE, $sc);
		return $feed;
	}

	/**
	 * Get a MODx chunk
	 *
	 * @param   string  $name	 chunk name
	 * @param   array   $properties	 chunk properties
	 * @return  object  returns	 modChunk
	 */
	protected function get_chunk($name, $properties = array())
	{
		$chunk = $this->modx->getChunk($name, $properties);
		return $chunk;
	}
	
	/**
	 * Insert CSS into the a documents head
	 *
	 * @param   array  $arr  css files
	 * @return  void
	 */
	protected function insert_css($stylesheets = array())
	{
		if( !is_array($stylesheets))
		{
			$stylesheet = str_split($stylesheet, strlen($stylesheet) );
		}

		foreach ($stylesheets as $css)
		{
			$this->modx->regClientCSS($css);
		}
	}

	/**
	 * Return array from comma separated arguments
	 *
	 * @param   string       $string  comma separated string
	 * @return  array|FALSE
	 */	
	protected function prepare_array($string, $separator = ',')
	{
		$csv = array_map('trim', explode($separator , $string) );
		$csv = ( is_array($csv) ) ? $csv : FALSE;
		return $csv;
	}
}
