Currency Converter for MODx
========================

A MODx snippet to convert currencies.

This extension uses the openexchangerates.org API to get the current exchange rates. 

**You will need to get and use your own App ID from https://openexchangerates.org/signup**

*Their free sign up is available from https://openexchangerates.org/signup/free*

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

Example Calls
-------------
Convert 250 GBP to Euro

```[[!CurrencyConverter? &from=`GBP` &amount=`250` &to=`EUR`]]```

A complete example (note that &signs is experimental):

```[[!CurrencyConverter? &from=`GBP` &amount=`1` &to=`KWD,OMR,GBP,USD,EUR,JPY,SOS,IRR` &signs=`||£|$|€|¥||`]]```


**Gold Coast Media Ltd**
