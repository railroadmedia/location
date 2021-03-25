railroad\/location
========================

Table of Contents
-----------

- [railroad\/location](#railroad--location)
    * [Table of Contents](#table-of-contents)
    * [REVIEW ME](#review-me)
    * [TL;DR:](#tl-dr-)
    * [Before Use](#before-use)
        + [Installation](#installation)
        + [Configuration](#configuration)
        + [Updating List of Countries We Cannot Ship To](#updating-list-of-countries-we-cannot-ship-to)
    * [Use](#use)
        + [Overview of available lists and tools](#overview-of-available-lists-and-tools)
        + [About examples in detailed sections below](#about-examples-in-detailed-sections-below)
        + [Lists of countries](#lists-of-countries)
            - [CountryListService::all()](#countrylistservice--all--)
            - [CountryListService::verbose()](#countrylistservice--verbose--)
            - [CountryListService::allWeCanShipTo()](#countrylistservice--allwecanshipto--)
            - [CountryListService::allWithCommonDuplicatedAtTop()](#countrylistservice--allwithcommonduplicatedattop--)
            - [CountryListService::allWeCanShipToWithCommonDuplicatedAtTop()](#countrylistservice--allwecanshiptowithcommonduplicatedattop--)
            - [CountryListService::unableToShipTo()](#countrylistservice--unabletoshipto--)
        + [Utility Methods](#utility-methods)
            - [LocationReferenceService::alpha2($name)](#locationreferenceservice--alpha2--name-)
            - [LocationReferenceService::name($alpha2)](#locationreferenceservice--name--alpha2-)
            - [LocationReferenceService::currencies($alpha2, $verbose = false)](#locationreferenceservice--currencies--alpha2---verbose---false-)
            - [LocationReferenceService::regions($alpha2, $returnSimpleArray = true)](#locationreferenceservice--regions--alpha2---returnsimplearray---true-)
    * [Issues, Uncertainties, and To-Dos](#issues--uncertainties--and-to-dos)
        + [Timezone information](#timezone-information)
        + [Data Hygiene](#data-hygiene)
        + [Crimea](#crimea)
        + [Standardizing names for Canadian Provices](#standardizing-names-for-canadian-provices)
    
<!-- ecotrust-canada.github.io/markdown-toc/ -->

REVIEW ME
----------------

**Please see notes in [REVIEWME.md](https://github.com/railroadmedia/location/blob/v2.0-/REVIEWME.md).**

Please comment|ask-questions in code or create issues liberally.

Thank you!


TL;DR:
---------------

`CountryListSevice::all()` is *the one always correct list* of countries for use in Musora applications and packages. It:

* returns an array of the "short English name" of all ISO 3166 countries, which exceptions as noted below
* countries are indexed by their "alpha-2" (two-letter) country code (as per ISO 3166-1)
* deviations from ISO 3166 are defined in the package configuration. They can be overriden in installaton configuration. Deviations include:
    * ISO 3166 standard "Taiwan (Province of China)" is renamed to "Taiwan"
    * "Kosovo" is added with the alpha-2 code of "XK"
* The source data for the list is the [league/iso3166](https://github.com/thephpleague/iso3166) package.

There are other methods available that return modified versions of the list as detailed below, but "\Railroad\Location\Services\CountryListService::all()" is the canonical list.

Before Use
-----------------

### Installation

1. install
1. run `artisan vendor:publish`
1. check & modify application's config files as needed
    1. config/app.php
        1. Add the package's LocationServiceProvider to the "providers" array as per below
        1. Add an aliases to services as noted below
    1. config/location.php

Add the package's LocationServiceProvider to the "providers" array in your application's config/app.php file:

```
    'providers' => [
        # ... probably some other stuff here above
        \Railroad\Location\Providers\LocationServiceProvider::class,
    ],
```

Add an alias in your application's *config/app.php* file. Ex:

```
    'aliases' => [
        # ... probably some other stuff here above
        'CountryListService' => \Railroad\Location\Services\CountryListService::class,
        'LocationReferenceService' => \Railroad\Location\Services\LocationReferenceService::class,
    ],
```

### Configuration

artisan vendor:publish will publish a location.php file to your application's config/ directory. In there you can specify values to override these default package values. If you do not provide values in your application's config/locations.php, the default in the package's [config/location.php](https://github.com/railroadmedia/location/blob/v2.0-/config/location.php) will be used.

Please note that options in the configuration are generally indexed by the country's ISO 3166-2 ("alpha2") two letter country-code. You can best find these codes generally in the sidebar of the country's Wikipedia page. But, for the "English short name", and for general canonical reference, please refer to the ISO's search tool at [iso.org/obp/ui/#search/code/](https://www.iso.org/obp/ui/#search/code/).  

### Updating List of Countries We Cannot Ship To

This is tricky because it must be manually updated. To do so, check for updates on these three pages:

1. The "ePost Global (PPDC, PPT)" column at https://www.epostglobalshipping.com/imp-serviceupdates.html
2. USPS list of sanctioned countries at https://about.usps.com/publications/pub699/pub699_online_017.htm
3. USPS Service Alerts, International service disruptions
https://about.usps.com/newsroom/service-alerts/international/welcome.htm

Note that the USPS Service Alerts—at the time of writing this, March 2021—has a section titled "Global Express Guaranteed® Service Suspensions and Disruptions". The section is not of concern to use because we don't use that service. Thus, some parsing of the page may be necessary when you check it to determine which sections are relevant.

There are two directories in this package's "data" file that are not used by the software, but rather for your use in manually updating this list. With some creative multi-cursor editing you can take the copied content from the relevant tables in the above pages and format them so that you can create a new file and compare them to previous versions of the similarily "manually-formatted" versions of that data. Basically epost is a table that when copied will give you TSV.

To format table data copied from epostGlobal:

1. create a new file names in similar fashion as previous ('YYYY-MM-DD.txt')
1. copy and paste the contents of the table at epostglobalshipping.com/imp-serviceupdates.html to that empty file
1. put an asterisk at the start of every line.
    * One way to do this is:
        1. Select all "tab" characters (in PHPStorm use "Select all occurences" hotkey, for me it's ctrl+shift+alt+j)
        1. press "Home" to put cursor at start of every line
        1. type an asterisk with cursor at start of every line
1. Select all "tab" characters (the spaces between each "cell value" will be a tab, just select that apparent whitespace, If you then select all occurences you'll be able to tell whether you got all the tab characters because they'll mostly be different lengths of "whitespace", and there'll be spaces *not* selected.)
1. press "Enter", and then "Tab" and you'll have then have an indented list.
1. As of writing this (March 2021), the relevant column is the third one, so if each country has four values listed (now) below it, delete the first, second, and fourth
1. You now have a file formatted as per the previous versions, compare it to the previous version.
1. update the config as needed.

Use
-------------

### Overview of available lists and tools

These will return arrays countries as described:

* CountryListService::all()
* CountryListService::verbose()
* CountryListService::allWeCanShipTo()
* CountryListService::allWithCommonDuplicatedAtTop()
* CountryListService::allWeCanShipToWithCommonDuplicatedAtTop()
* CountryListService::unableToShipTo()

These will return the described information about the country:

* LocationReferenceService::alpha2($name)
* LocationReferenceService::name($alpha2)
* LocationReferenceService::currencies($alpha2, $verbose = false)
* LocationReferenceService::regions($alpha2, $returnSimpleArray = true) // call "getSubdivisions" instead?

These are advanced. Pay no attention to the man behind the curtain. They can be useful for extension though:

* LocationReferenceService::countriesMasterList()
* LocationReferenceService::countriesListCreator($allWeCanShipTo = false, $copyCommonToTop = false)
* LocationReferenceService::countryNamesFromOtherServices($service = null)

### About examples in detailed sections below

Below examples assume that above their usage, either a use-statement such as the following is present:

```
use Railroad\Location\Services\LocationReferenceService;
use Railroad\Location\Services\CountryListService;
```

Or an alias is register in your application's *config/app.php* file. Ex:

```
    'aliases' => [
        'CountryListService' => \Railroad\Location\Services\CountryListService::class,
        'LocationReferenceService' => \Railroad\Location\Services\LocationReferenceService::class,
    ]
```

Note that for all examples below, the config('location') is configured as per the default config/location.php described above.


### Lists of countries

#### CountryListService::all()

**This is the easiest-to-use canonical list of countries.**

Note the following:

1. the countries we've listed as unable to ship to in config('location.'countries-unable-to-ship-to', ex: Syrian Arab Republic) *are* included here
1. "TW" is "Taiwan" (not "Taiwan (Province of China)" as per ISO 3166) as defined in our configuation for "countries-name-altered"
1. "Kosovo" is present with the alpha-2 code "XK" as per our "user-defined" configuration
1. the "common-at-top" countries are not copied to the top

Example:

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

#### CountryListService::verbose()

Regarding the configuration values for 'countries-unable-to-ship-to', 'countries-name-altered', 'common-at-top', and 'user-defined', this will return the same permutations as the `all()` method:

1. the countries we've listed as unable to ship to in config('location.'countries-unable-to-ship-to', ex: Syrian Arab Republic) *are* included here
1. "TW" is "Taiwan" (not "Taiwan (Province of China)" as per ISO 3166) as defined in our configuation for "countries-name-altered"
1. "Kosovo" is present with the alpha-2 code "XK" as per our "user-defined" configuration
1. the "common-at-top" countries are not copied to the top

Abridged Example:

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

#### CountryListService::allWeCanShipTo()

Same as `all()`, except excluding an countries defined in 'countries-unable-to-ship-to'. Note Zimbabwe (ZY) missing whereas in `all()` it's second-to-last.

Example:

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

#### CountryListService::allWithCommonDuplicatedAtTop()

Countries defined in config 'common-at-top' are *duplicated* at top of list. Note this array is indexed rather than keyed by alpha-2 country codes. This is because the countries copied to top are repeated and thus associative index is not possible.

Note that the countries will also appear in their alphabetical location. This is for better UX in case a user didn't realize their country was at the top.

Example:

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

#### CountryListService::allWeCanShipToWithCommonDuplicatedAtTop()

Like `allWithCommonDuplicatedAtTop()` but with countries excluding countries defined in config's 'countries-unable-to-ship-to'. Note the reduced number of items indicated by the last index being 238 rather than 253 for `allWithCommonDuplicatedAtTop()`.

Example:

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

#### CountryListService::unableToShipTo()

Returns only the countries defined in the above noted "unable-to-ship-to" configuration.

Example:

```
array (
  'BM' => 'Bermuda',
  'BN' => 'Brunei Darussalam',
  'BO' => 'Bolivia (Plurinational State of)',
  'BT' => 'Bhutan',
  'BW' => 'Botswana',
  'DM' => 'Dominica',
  'DO' => 'Dominican Republic',
  'KY' => 'Cayman Islands',
  'LY' => 'Libya',
  'PG' => 'Papua New Guinea',
  'SS' => 'South Sudan',
  'SY' => 'Syrian Arab Republic',
  'TJ' => 'Tajikistan',
  'YE' => 'Yemen',
  'ZW' => 'Zimbabwe',
)
```

### Utility Methods

#### LocationReferenceService::alpha2($name)

Given above noted configuration

* passed "Canada", will return "CA"
* passed "Taiwan", will return "TW"
* passed "Kosovo", will return "XK"
* passed "acountrythatdoesntexist", will return boolean false
* passed "Taiwan (Province of China)", will return boolean false
    * because overriden by 'alter-name' configuration


#### LocationReferenceService::name($alpha2)

* passed "CA", will return "Canada"
* passed "TW", will return "Taiwan"
* passed "XK", will return "Kosovo"
* passed "analpha2codethatdoesntexist", will return boolean false


#### LocationReferenceService::currencies($alpha2, $verbose = false)

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


#### LocationReferenceService::regions($alpha2, $returnSimpleArray = true)

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

Issues, Uncertainties, and To-Dos
--------------------------------------------

[comment]: <> (### Missing data for user-defined countries)

[comment]: <> (If using an external package to retrieve information, will user-defined countries' information &#40;set in config&#41; be returned?)

[comment]: <> (Namely, if calling on any feature of package that we're using for getting info about a country &#40;like league/iso3166 and sokil/php-isocodes&#41; if the param passed species an user-defined country, are we successfully prempting the typical call to the 3rd-party package and rather just returning the user-defined info we set in config?)

[comment]: <> (I believe so, but am unsure)

### Timezone information

How to get this? We have something like this on the schedule page for Drumeo, but what does it look like?

Route for schedule page is: GET [/members/schedule](https://drumeo.com/members/schedule) (uses [\App\Http\Controllers\Content\ScheduleController@catalogue](https://github.com/railroadmedia/drumeo/blob/master/laravel/app/Http/Controllers/Content/ScheduleController.php)) ([line 42](https://github.com/railroadmedia/drumeo/blob/3d45018aa6afbc9fd8b4201ddb212c81541e6b76/laravel/app/Http/Controllers/Content/ScheduleController.php#L42) at time or writing)

There are two methods of interest:
1. [CalendarService@getTimezone](https://github.com/railroadmedia/drumeo/blob/master/laravel/app/legacy_classes/services/CalendarService.php), which gets the timezone from the request, or from the member's record in the database if available. ([line 25](https://github.com/railroadmedia/drumeo/blob/a1593895a570ffc7c4f361cf8f5e2e846b8f69ee/laravel/app/legacy_classes/services/CalendarService.php#L25) at time of writing)
2. [ScheduleController@timezoneList](https://github.com/railroadmedia/drumeo/blob/master/laravel/app/Http/Controllers/Content/ScheduleController.php), which assembles a nice list of timezones that a user can select from ([line 76](https://github.com/railroadmedia/drumeo/blob/3d45018aa6afbc9fd8b4201ddb212c81541e6b76/laravel/app/Http/Controllers/Content/ScheduleController.php#L76) at time of writing). It uses PHP's [DateTimeZone](https://www.php.net/manual/en/class.datetimezone.php) class.

### Data Hygiene

Currently the database (ex: musora_laravel.ecommerce_addresses) stores a non-standard mix of values for countries (and subdivisions). Ideally it would be standardized. Currently however I don't think any mismatches will break anything. The only matching of country name I believe is in calculating taxes for if the country is "Canada", and that's pretty consistently just called "Canada"—though note that there are 284 addresses with "CAN" as the country.

I'm going to do another check of all automated tests. Last time there were no issues, but changes have been made since then. They're functionally trivial, but it feels like the least I can do. Otherwise I feel like I'd have to go through all functionality, and that is likely not required and thus even more so an unattractive proposition.

### Crimea

Countries we can't ship to are no worries, but there is *one* subdivision (read: province, state, administrative district, etc) that we cannot ship to. This is not addressed currently because the implementation of this package have no provision for differentiating between subdivisions in any country other than Canada. Though, at the moment, it's only one small area, so is not of concern.

### Standardizing names for Canadian Provices

The currently used names and abreviations for Canadian provinces are not standardized. I don't think it will cause any issues, but I think we should ultimately replace the province names with the their ISO 3166-2 codes (ex: "BC" would be "CA-BC" iirc). We would then get the names (ex: "British Columbia") from some service method by supplying the ISO 3166-2 code (ex: "CA-BC"). This would lay the foundation for using subdivision information for all countries (which is available by country-code from the 3rd-party packages we're using).

