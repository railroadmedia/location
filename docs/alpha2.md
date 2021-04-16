
#### LocationReferenceService::alpha2($name)


* `\Railroad\Location\Services\CountryListService::unableToShipTo()`

These will return the described information about the country:

Called like this: `\Railroad\Location\Services\LocationReferenceService::name($alpha2)`. Some examples (given configuration noted in main readme) are:

* passed "Canada", will return "CA"
* passed "Taiwan", will return "TW"
* passed "Kosovo", will return "XK"
* passed "acountrythatdoesntexist", will return boolean false
* passed "Taiwan (Province of China)", will return boolean false
    * because overriden by 'alter-name' configuration

