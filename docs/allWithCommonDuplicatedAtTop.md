
#### CountryListService::allWithCommonDuplicatedAtTop()

Countries defined in config 'common-at-top' are *duplicated* at top of list. Note this array is indexed rather than keyed by alpha-2 country codes. This is because the countries copied to top are repeated and thus associative index is not possible.

Note that the countries will also appear in their alphabetical location. This is for better UX in case a user didn't realize their country was at the top.

Calling `\Railroad\Location\Services\CountryListService::allWithCommonDuplicatedAtTop()` will return an array like:

```
array (
  0 => 'United States of America',                              // 1st appearance
  1 => 'Canada',                                                // 1st appearance
  2 => 'United Kingdom of Great Britain and Northern Ireland',  // 1st appearance
  3 => 'Australia',                                             // 1st appearance
  4 => 'Afghanistan',
  5 => 'Åland Islands',
  // ... shortened for brevity
  15 => 'Armenia',
  16 => 'Aruba',
  17 => 'Australia',                                            // 2nd appearance
  18 => 'Austria',
  19 => 'Azerbaijan',
  // ... shortened for brevity
  42 => 'Cambodia',
  43 => 'Cameroon',
  44 => 'Canada',                                               // 2nd appearance
  45 => 'Cayman Islands',
  46 => 'Central African Republic',
  // ... shortened for brevity
```
