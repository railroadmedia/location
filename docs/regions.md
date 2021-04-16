
#### LocationReferenceService::regions($alpha2, $returnSimpleArray = true)

* `\Railroad\Location\Services\LocationReferenceService::regions($alpha2, $returnSimpleArray = true)` /* todo: change method name to "getSubdivisions"? */


If passed—for example—`'CA'`, will return:

```
array (
  'CA-AB' => 'Alberta',
  'CA-BC' => 'British Columbia',
  'CA-MB' => 'Manitoba',
  'CA-NB' => 'New Brunswick',
  'CA-NL' => 'Newfoundland and Labrador',
  'CA-NS' => 'Nova Scotia',
  'CA-NT' => 'Northwest Territories',
  'CA-NU' => 'Nunavut',
  'CA-ON' => 'Ontario',
  'CA-PE' => 'Prince Edward Island',
  'CA-QC' => 'Quebec',
  'CA-SK' => 'Saskatchewan',
  'CA-YT' => 'Yukon Territory',
)
```