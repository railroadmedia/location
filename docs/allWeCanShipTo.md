
#### CountryListService::allWeCanShipTo()

Same as `all()`, except excluding an countries defined in 'countries-unable-to-ship-to'. Note Zimbabwe (ZY) missing whereas in `all()` it's second-to-last.

Calling `\Railroad\Location\Services\CountryListService::allWeCanShipTo()` will return an array like:

```
array (
  'AF' => 'Afghanistan',
  'AX' => 'Åland Islands',
  'AL' => 'Albania',
  'DZ' => 'Algeria',
  'AS' => 'American Samoa',
  'AD' => 'Andorra',
  'AO' => 'Angola',
  'AI' => 'Anguilla',
  'AQ' => 'Antarctica',
  'AG' => 'Antigua and Barbuda',
  // ... shortened for brevity
  'ZA' => 'South Africa',
  'GS' => 'South Georgia and the South Sandwich Islands',
  'ES' => 'Spain',
  'LK' => 'Sri Lanka',
  'SD' => 'Sudan',
  'SR' => 'Suriname',
  'SJ' => 'Svalbard and Jan Mayen',
  'SE' => 'Sweden',
  'CH' => 'Switzerland',
  'TW' => 'Taiwan',
  'TZ' => 'Tanzania, United Republic of',
  // ... shortened for brevity
  'AE' => 'United Arab Emirates',
  'GB' => 'United Kingdom of Great Britain and Northern Ireland',
  'US' => 'United States of America',
  'UM' => 'United States Minor Outlying Islands',
  'UY' => 'Uruguay',
  'UZ' => 'Uzbekistan',
  'VU' => 'Vanuatu',
  'VE' => 'Venezuela (Bolivarian Republic of)',
  'VN' => 'Viet Nam',
  'VG' => 'Virgin Islands (British)',
  'VI' => 'Virgin Islands (U.S.)',
  'WF' => 'Wallis and Futuna',
  'EH' => 'Western Sahara',
  'ZM' => 'Zambia',
  'XK' => 'Kosovo',
)
```
