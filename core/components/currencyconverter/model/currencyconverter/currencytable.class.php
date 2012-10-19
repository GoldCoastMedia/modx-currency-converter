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
		'include' => 'all',
		'rowtpl'  => 'currencyconvert',
		'tpl'     => 'currencyconvert',
	);
	
	protected function generate()
	{
		$ouput = NULL;
		$feed = json_decode($this->feed_cache());
		
		print_r($feed);
	}
	
	
}
