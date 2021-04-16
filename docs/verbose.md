
#### CountryListService::verbose()

Regarding the configuration values for 'countries-unable-to-ship-to', 'countries-name-altered', 'common-at-top', and 'user-defined', this will return the same permutations as the `all()` method:

1. the countries we've listed as unable to ship to in config('location.'countries-unable-to-ship-to', ex: Syrian Arab Republic) *are* included here
1. "TW" is "Taiwan" (not "Taiwan (Province of China)" as per ISO 3166) as defined in our configuation for "countries-name-altered"
1. "Kosovo" is present with the alpha-2 code "XK" as per our "user-defined" configuration
1. the "common-at-top" countries are not copied to the top

Calling `\Railroad\Location\Services\CountryListService::verbose()` will return:

```
array (
  0 =>
  array (
    'name' => 'Afghanistan',
    'alpha2' => 'AF',
    'alpha3' => 'AFG',
    'numeric' => '004',
    'currency' =>
    array (
      0 => 'AFN',
    ),
  ),
  1 =>
  array (
    'name' => 'Åland Islands',
    'alpha2' => 'AX',
    'alpha3' => 'ALA',
    'numeric' => '248',
    'currency' =>
    array (
      0 => 'EUR',
    ),
  ),
  # ... many countries omitted for brevity from this example
  248 =>
  array (
    'name' => 'Zimbabwe',
    'alpha2' => 'ZW',
    'alpha3' => 'ZWE',
    'numeric' => '716',
    'currency' =>
    array (
      0 => 'BWP',
      1 => 'EUR',
      2 => 'GBP',
      3 => 'USD',
      4 => 'ZAR',
    ),
  ),
  249 =>
  array (
    'name' => 'Kosovo',
    'alpha2' => 'XK',
    'alpha3' => 'XKS',
    'numeric' => 900,
    'currency' =>
    array (
      0 => 'EUR',
    ),
  ),
)
```