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
		'amount'            => 0,
		'appid'             => NULL,
		'cachelifetime'     => 1800,
		'cachename'         => 'openexchangerates',
		'decimalplaces'     => 2,
		'decimalpoint'      => '.',
		'from'              => 'USD',
		'to'                => NULL,
		'method'            => 'curl',
		'moneyformat'       => FALSE,
		'round'             => FALSE,
		'signs'             => NULL,
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
	protected $api_url   = '';

	public function __construct(modX &$modx, array &$config)
	{
		$this->modx =& $modx;
		$this->modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
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
	}
	
	public function run() 
	{
	}

	/*
	 *
	 */
	protected function convert()
	{
	}

	/*
	 *
	 */
	protected function format()
	{
	}

	/**
	 * Check and return if the feeds JSON is valid
	 *
	 * @param   string  $feed  the JSON feed
	 * @return  bool
	 */
	protected function valid_feed($feed)
	{
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
	}

	/**
	 * Build the request URL
	 *
	 * @return  string|bool
	 */
	protected function build_request_uri()
	{
	}

	/**
	 * Get the weather feed
	 *
	 * @param   string  $url     The URL
	 * @param   string  $method  The method used to fetch the feed
	 * @return  bool
	 */
	protected function get_feed($url = NULL, $method = NULL, $timeout = 5)
	{
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
		$sc = stream_context_create(array('http' => array('timeout' => (int) $timeout)));
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
			$stylesheet = str_split($stylesheet, strlen($stylesheet));
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
		$csv = array_map('trim', explode($separator , $string));
		$csv = ( is_array($csv) ) ? $csv : FALSE;

		return $csv;
	}
}
