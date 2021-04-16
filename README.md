railroad\/location
========================

Table of Contents
-----------

- [railroad\/location](#railroad--location)
    * [Table of Contents](#table-of-contents)
    * [TLDR for non-technical users](#tldr-for-non-technical-users)
        + [1. the list of countries that we use](#1-the-list-of-countries-that-we-use)
        + [2. the list of countries we can't ship to](#2-the-list-of-countries-we-can-t-ship-to)
        + [3. info about special cases](#3-info-about-special-cases)
    * [TLDR for technical users](#tldr-for-technical-users)
    * [Code Review](#code-review)
    * [Before Use](#before-use)
        + [Installation](#installation)
        + [Configuration](#configuration)
        + [Updating List of Countries We Cannot Ship To](#updating-list-of-countries-we-cannot-ship-to)
    * [Use](#use)
        + [Overview of available lists and tools](#overview-of-available-lists-and-tools)
    * [Issues, Uncertainties, and To-Dos](#issues--uncertainties--and-to-dos)
        + [Timezone information](#timezone-information)
        + [Data Hygiene](#data-hygiene)
            - [Standardizing names for Canadian Provices](#standardizing-names-for-canadian-provices)
        + [Crimea](#crimea)

<!-- ecotrust-canada.github.io/markdown-toc/ -->

TLDR for non-technical users
---------------

Welcome. If you're looking for one of three things these sections can help you:

1. the list of countries that we use
2. the list of countries we can't ship to
3. info about special cases

See the relevant sub-section below...

Note that sections 2 and 3 use a text file you'll find at [github.com/railroadmedia/location/blob/v2.0-/config/location.php](https://github.com/railroadmedia/location/blob/v2.0-/config/location.php).

This is a file used by the programs running on our servers and the information you'll see is formatted for interpretation by the computer. However it is also human readable and while it may look weird at first, but it's actually very straightforward (we programmers are actually quite simple minded folk and generally need all the help we can get so we try to keep things simple).


### 1. the list of countries that we use

We use the International Standards Organization (ISO) 3166 standard ([wikipedia](https://en.wikipedia.org/wiki/ISO_3166)).

You can explore the list [here](https://www.iso.org/obp/ui/#search/code/).

There are some cases where we deviate from that standard. See the "info about special cases" section below.

### 2. the list of countries we can't ship to

You can see these in their current form by viewing the `'countries-unable-to-ship-to'` section of [the above mentioned text file](https://github.com/railroadmedia/location/blob/v2.0-/config/location.php).

The countries are listed by their two-letter country codes. The the right of each there should also be the name of the country. These names are not readable by the computer as anything after a pound-symbol (`#`) on a line is ignored. Thus, the country name may not necessarily be the ISO 3166 standard. If you need to confirm the "proper" ISO 3166 name, you can either search online generally, or specifically by that two-letter code using the [ISO site](https://www.iso.org/obp/ui/#search/code/) (also listed above).

This list must be manually updated however and so it can be out-of-date. If you need to directly check the source of information used to generate this list, you'll see them there in the list each as a kind of heading.

### 3. info about special cases

You can see these in their current form by viewing one of the below listed sections of the [the above mentioned text file](https://github.com/railroadmedia/location/blob/v2.0-/config/location.php).

* `'countries-name-altered'` lists countries whose English short name we alter in all instances of usage. The first two-letter part of each line is the two-letter country code
* `'user-defined'` adds countries that are not on ISO's list. While there is currently just one country there now, if there was more than one, you would see a visually-sensible organization of information—each "bundle" of lines enclosed in square brackets (`[` and `]`) is an "object" so to speak.

Also, we can't ship to Crimea. See the "[Crimea](#crimea)" section below for techincal details.

TLDR for technical users
---------------

`CountryListSevice::all()` is *the one always correct list* of countries for use in Musora applications and packages. It:

* returns an array of the "short English name" of all ISO 3166 countries, which exceptions as noted below
* countries are indexed by their "alpha-2" (two-letter) country code (as per ISO 3166-1)
* deviations from ISO 3166 are defined in the package configuration. They can be overriden in installaton configuration. Deviations include:
    * ISO 3166 standard "Taiwan (Province of China)" is renamed to "Taiwan"
    * "Kosovo" is added with the alpha-2 code of "XK"
* The source data for the list is the [league/iso3166](https://github.com/thephpleague/iso3166) package.

There are other methods available that return modified versions of the list as detailed below, but "\Railroad\Location\Services\CountryListService::all()" is the canonical list.

There is also a manually updated list of countries that we cannot ship to. This is pulled from three sources. See notes below regarding this.

It uses two external repositories ([thephpleague/iso3166](https://github.com/thephpleague/iso3166) and [sokil/php-isocodes](https://github.com/sokil/php-isocodes)) that provides and up-to-date country data as per the **ISO 3166 standard**([read about it here](https://en.wikipedia.org/wiki/ISO_3166) and [explore it here](https://www.iso.org/iso-3166-country-codes.html)). Thus, to update the country list we need only run `composer update` in the applications that implement this package.

Code Review
------------------

This package in implemented in a number of other repos, and you can see [REVIEWME.md](https://github.com/railroadmedia/location/blob/v2.0-/REVIEWME.md) for help in seeing how this package is used.

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
<!-- note that this section title is linked and editing it requires you then appropriately update instances of "#updating-list-of-countries-we-cannot-ship-to" that link to it -->

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

Methods of `CountryListService`

* [all](docs/all.md)
* [verbose](docs/verbose.md)
* [allWeCanShipTo](docs/allWeCanShipTo.md)
* [allWithCommonDuplicatedAtTop](docs/allWithCommonDuplicatedAtTop.md)
* [allWeCanShipToWithCommonDuplicatedAtTop](docs/allWeCanShipToWithCommonDuplicatedAtTop.md)
* [unableToShipTo](docs/unableToShipTo.md)

Methods of `LocationReferenceService`

* [alpha2](docs/alpha2.md)
* [name](docs/name.md)
* [currencies](docs/currencies.md)
* [regions](docs/regions.md)

Advanced methods you shouldn't use unless you need them for extension and grok the source to understand their potential usage:

* LocationReferenceService::countriesMasterList()
* LocationReferenceService::countriesListCreator($allWeCanShipTo = false, $copyCommonToTop = false)
* LocationReferenceService::countryNamesFromOtherServices($service = null)


Issues, Uncertainties, and To-Dos
--------------------------------------------

### Timezone information

How to get this? We have something like this on the schedule page for Drumeo, but what does it look like?

Route for schedule page is: GET [/members/schedule](https://drumeo.com/members/schedule) (uses [\App\Http\Controllers\Content\ScheduleController@catalogue](https://github.com/railroadmedia/drumeo/blob/master/laravel/app/Http/Controllers/Content/ScheduleController.php)) ([line 42](https://github.com/railroadmedia/drumeo/blob/3d45018aa6afbc9fd8b4201ddb212c81541e6b76/laravel/app/Http/Controllers/Content/ScheduleController.php#L42) at time or writing)

There are two methods of interest:
1. [CalendarService@getTimezone](https://github.com/railroadmedia/drumeo/blob/master/laravel/app/legacy_classes/services/CalendarService.php), which gets the timezone from the request, or from the member's record in the database if available. ([line 25](https://github.com/railroadmedia/drumeo/blob/a1593895a570ffc7c4f361cf8f5e2e846b8f69ee/laravel/app/legacy_classes/services/CalendarService.php#L25) at time of writing)
2. [ScheduleController@timezoneList](https://github.com/railroadmedia/drumeo/blob/master/laravel/app/Http/Controllers/Content/ScheduleController.php), which assembles a nice list of timezones that a user can select from ([line 76](https://github.com/railroadmedia/drumeo/blob/3d45018aa6afbc9fd8b4201ddb212c81541e6b76/laravel/app/Http/Controllers/Content/ScheduleController.php#L76) at time of writing). It uses PHP's [DateTimeZone](https://www.php.net/manual/en/class.datetimezone.php) class.

### Data Hygiene

Currently the database (ex: musora_laravel.ecommerce_addresses) stores a non-standard mix of values for countries (and subdivisions). Ideally it would be standardized. Currently however I don't think any mismatches will break anything. The only matching of country name I believe is in calculating taxes for if the country is "Canada", and that's pretty consistently just called "Canada"—though note that there are 284 addresses with "CAN" as the country.

I'm going to do another check of all automated tests. Last time there were no issues, but changes have been made since then. They're functionally trivial, but it feels like the least I can do. Otherwise I feel like I'd have to go through all functionality, and that is likely not required and thus even more so an unattractive proposition.

#### Standardizing names for Canadian Provices

The currently used names and abreviations for Canadian provinces are not standardized. I don't think it will cause any issues, but I think we should ultimately replace the province names with the their ISO 3166-2 codes (ex: "BC" would be "CA-BC" iirc). We would then get the names (ex: "British Columbia") from some service method by supplying the ISO 3166-2 code (ex: "CA-BC"). This would lay the foundation for using subdivision information for all countries (which is available by country-code from the 3rd-party packages we're using).

### Crimea

Countries we can't ship to are no worries, but there is *one* subdivision (read: province, state, administrative district, etc) that we cannot ship to. This is not addressed currently because the implementation of this package have no provision for differentiating between subdivisions in any country other than Canada. Though, at the moment, it's only one small area, so is not of concern.
