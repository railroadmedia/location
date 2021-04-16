
#### CountryListService::allWeCanShipToWithCommonDuplicatedAtTop()

Like `allWithCommonDuplicatedAtTop()` but with countries excluding countries defined in config's 'countries-unable-to-ship-to'. Note the reduced number of items indicated by the last index being 238 rather than 253 for `allWithCommonDuplicatedAtTop()`.


Calling `\Railroad\Location\Services\CountryListService::allWeCanShipToWithCommonDuplicatedAtTop()` will return something like:

```
array (
  0 => 'United States of America',
  1 => 'Canada',
  2 => 'United Kingdom of Great Britain and Northern Ireland',
  3 => 'Australia',
  4 => 'Afghanistan',
  5 => 'Åland Islands',
  6 => 'Albania',
  7 => 'Algeria',
  8 => 'American Samoa',
  9 => 'Andorra',
  10 => 'Angola',
  11 => 'Anguilla',
  12 => 'Antarctica',
  13 => 'Antigua and Barbuda',
  // ... shortened for brevity
  200 => 'South Africa',
  201 => 'South Georgia and the South Sandwich Islands',
  202 => 'Spain',
  203 => 'Sri Lanka',
  204 => 'Sudan',
  205 => 'Suriname',
  206 => 'Svalbard and Jan Mayen',
  207 => 'Sweden',
  208 => 'Switzerland',
  209 => 'Taiwan',
  210 => 'Tanzania, United Republic of',
  // ... shortened for brevity
  224 => 'United Arab Emirates',
  225 => 'United Kingdom of Great Britain and Northern Ireland',
  226 => 'United States of America',
  227 => 'United States Minor Outlying Islands',
  228 => 'Uruguay',
  229 => 'Uzbekistan',
  230 => 'Vanuatu',
  231 => 'Venezuela (Bolivarian Republic of)',
  232 => 'Viet Nam',
  233 => 'Virgin Islands (British)',
  234 => 'Virgin Islands (U.S.)',
  235 => 'Wallis and Futuna',
  236 => 'Western Sahara',
  237 => 'Zambia',
  238 => 'Kosovo',
)
```
