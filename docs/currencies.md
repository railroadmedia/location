
#### LocationReferenceService::currencies($alpha2, $verbose = false)

Calling `\Railroad\Location\Services\LocationReferenceService::currencies($alpha2, $verbose = false)` will return something like:

Passed only `'CA'`, will return:

```
array (
  0 => 'CAD',
)
```

If—with first argument again "CA"—a second argument is provided that is `true`, the result will be:

```
array (
  'CAD' =>
  array (
    'name' => 'Canadian Dollar',
    'local-name' => 'Canadian Dollar',
    'numeric-code' => '124',
  ),
)
```

For a country with several currencies—for example Zimbabwe (`'ZW'`)—the result will look like this:

```
array (
  0 => 'BWP',
  1 => 'EUR',
  2 => 'GBP',
  3 => 'USD',
  4 => 'ZAR',
)
```

If—again first param is "ZW"—the second param `true` we get:

```
array (
  'BWP' =>
  array (
    'name' => 'Pula',
    'local-name' => 'Pula',
    'numeric-code' => '072',
  ),
  'EUR' =>
  array (
    'name' => 'Euro',
    'local-name' => 'Euro',
    'numeric-code' => '978',
  ),
  'GBP' =>
  array (
    'name' => 'Pound Sterling',
    'local-name' => 'Pound Sterling',
    'numeric-code' => '826',
  ),
  'USD' =>
  array (
    'name' => 'US Dollar',
    'local-name' => 'US Dollar',
    'numeric-code' => '840',
  ),
  'ZAR' =>
  array (
    'name' => 'Rand',
    'local-name' => 'Rand',
    'numeric-code' => '710',
  ),
)
```
