#### CountryListService::all()

**This is the easiest-to-use canonical list of countries.**

Note the following:

1. includes the countries we've listed as unable to ship to in config('location.'countries-unable-to-ship-to', ex: Syrian Arab Republic) *are* included here
1. "TW" is "Taiwan" (not "Taiwan (Province of China)" as per ISO 3166) as defined in our configuation for "countries-name-altered"
1. "Kosovo" is present with the alpha-2 code "XK" as per our "user-defined" configuration
1. the "common-at-top" countries are not copied to the top

calling `\Railroad\Location\Services\CountryListService::all()` will return an array like:

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
  'SS' => 'South Sudan',
  'ES' => 'Spain',
  'LK' => 'Sri Lanka',
  'SD' => 'Sudan',
  'SR' => 'Suriname',
  'SJ' => 'Svalbard and Jan Mayen',
  'SE' => 'Sweden',
  'CH' => 'Switzerland',
  'SY' => 'Syrian Arab Republic',
  'TW' => 'Taiwan',
  'TJ' => 'Tajikistan',
  'TZ' => 'Tanzania, United Republic of',
  // ... shortened for brevity
  'AE' => 'United Arab Emirates',
  'GB' => 'United Kingdom of Great Britain and Northern Ireland',
  'US' => 'United States of America',
  'UM' => 'United States Minor Outlying Islands',
  // ... shortened for brevity
  'VE' => 'Venezuela (Bolivarian Republic of)',
  'VN' => 'Viet Nam',
  'VG' => 'Virgin Islands (British)',
  'VI' => 'Virgin Islands (U.S.)',
  'WF' => 'Wallis and Futuna',
  'EH' => 'Western Sahara',
  'YE' => 'Yemen',
  'ZM' => 'Zambia',
  'ZW' => 'Zimbabwe',
  'XK' => 'Kosovo',
)
```
