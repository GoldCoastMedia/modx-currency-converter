Currency Converter for MODx
========================

A MODx snippet to convert currencies. Roughly 150 currencies are available.

This extension uses the openexchangerates.org API to get the current exchange rates. 

**You will need to get and use your own App ID from https://openexchangerates.org/signup**

Please read the documentation at http://www.goldcoastmedia.co.uk/tools/modx-currency-converter/
for usage, examples, parameters and placeholder information.

*NOTE*: *curl* or *file_get_contents* must be able to read remote files. You
can set which method to use with ```&method=`````.

Installation
-----------
Install via MODx package manager and change settings via Settings > System Settings.

Documentation
------------
Full detailed documentation available at:
http://www.goldcoastmedia.co.uk/tools/modx-currency-converter/

Basic Example Calls
-------------------
NOTE: All parameters are *not* case-sensitive.

Convert 1 GBP to USD,EUR,JPY

```[[!CurrencyConverter? &from=`GBP`  &to=`USD,EUR,JPY`]]```

Convert 250 USD to GBP

```[[!CurrencyConverter? &amount=`250` &from=`USD`  &to=`GBP`]]```

Convert 1000 GBP to all available currencies

```[[!CurrencyConverter? &amount=`1000` &from=`GBP` &to=`*`]]```

Calling the snippet without any parameters will not produce any output. It will however update the 
currency exchange rate if the cache has expired. This can be used to update the currency exchange
rates via a cron job.

```[[!CurrencyConverter]]```

Format the currency output:

```[[!CurrencyConverter? &amount=`80` &from=`GBP`  &to=`IRR` &decimalplaces=`2` &decimalpoint=`,` &thousandseparator=`.`]]```

Change the template (chunk) to use:

```[[!CurrencyConverter? &amount=`20` &to=`LYD` &tpl=`some_other_chunk`]]```

Round up:
```[[!CurrencyConverter? &from=`IRR`  &to=`AUD` &round=`up`]]```

Round down:
```[[!CurrencyConverter? &from=`GIP`  &to=`KWD` &round=`down`]]```

Change cURL to file_get_contents:

```[[!CurrencyConverter? &method=`file_get_contents` &amount=`10` &from=`EGP`  &to=`GBP`]]```

Disable snippet output:

```[[!CurrencyConverter? &output=`0`]]```

Output the currency exchange update time:

```[[!CurrencyConverter? &updated=`1`]]```


Advanced Example Calls
-----------------------

The money_format function can also be used (not available on Windows). See [money_format](http://php.net/manual/en/function.money-format.php).

```[[!CurrencyConverter? &amount=`1450` &from=`USD`  &to=`EUR` &moneyformat=`%.2n`]]```

Overwriting System Settings

```[[!CurrencyConverter? &amount=`50` &from=`GBP`  &to=`HKD` &appid=`APPID` &cachelifetime=`3600` &timeout=`2`]]```

Change the 'updated' date format. See [date](http://php.net/manual/en/function.date.php).

```[[!CurrencyConverter? &updated=`1` &updatedformat=`d-m-y`]]```


**Gold Coast Media Ltd**
