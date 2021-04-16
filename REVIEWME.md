Notes on reviewing railroadmedia\/location package
===================================

TL;DR:
--------------------

This update is to create a standardized list of countries. The point of interest here are the CountryListService and LocationReference classes. The static methods exist so that they—like the methods they're replacing—can be called in views. However, in developing this I ended up making most methods static as I wasn't sure quite what I would need. I feel this could use some light refactoring, but if I recall correctly most of the methods are also potentially useful anywhere so maybe not.

The [readme](https://github.com/railroadmedia/location/blob/v2.0-/README.md) details the features and configuration options, and I believe it's up-to-date.

Also please note that the bottom of the readme has miscellaneous remaining concerns and potential future improvements.

Please comment|ask-questions in code or create issues liberally.

Thank you!

Notes about integration and development
--------------------

* current production branch: [master](https://github.com/railroadmedia/location/tree/master)
* proposed feature branch: [v2.0-](https://github.com/railroadmedia/location/tree/v2.0-)
* link to github compare: [compare/master...v2.0-](https://github.com/railroadmedia/location/compare/master...v2.0-)

This package is used in the following five repos. Note that one of the five (ecommerce) is also itself used in the other four.

Each of these links is to github's comparison of the feature branch onto it's "source" (I don't know what the technical git terminology for this is tbh):

* [js-helper-functions](https://github.com/railroadmedia/js-helper-functions/compare/master...railroad-location-package-update)
    * node module 
* [ecommerce](https://github.com/railroadmedia/ecommerce/compare/2.4-...2.4-railroad-location-package-update)
    * note that—in addition to this requiring the location package—this also is a requirement in the four applications below, which themselves also require the location package.
* [musora](https://github.com/railroadmedia/musora/compare/master...railroad-location-package-update)
* [drumeo](https://github.com/railroadmedia/drumeo/compare/master...railroad-location-package-update)
* [pianote](https://github.com/railroadmedia/pianote/compare/master...railroad-location-package-update)
* [guitareo](https://github.com/railroadmedia/guitareo/compare/master...railroad-location-package-update)

To test these branches, you can pull them and then run `composer install` as the composer.json (and .lock) are currently configured for the dev branches, and to address the minor dependancy-hell that comes from ecommerce also having the location package as a requirement.

To work on this package you can symlink to the appropriate directory in the application's vendor directory (remember `r symlink` in rail-environment-manager).

To play around directly with the code, copy-paste the below code somewhere like an artisan command and toggle sections as desired:

```
        $country = 'Canada';
        $alpha2 = 'CA';

//        $country = 'Antarctica';
//        $alpha2 = 'AQ';

//        $country = 'Zimbabwe';
//        $alpha2 = 'ZW';

//        $country = 'Taiwan';
//        $country = 'Taiwan (Province of China)';
//        $alpha2 = 'TW';

//        $country = 'Kosovo';
//        $alpha2 = 'XK';

        
        var_export(CountryListService::all());
//        var_export(CountryListService::verbose());
//        var_export(CountryListService::excludeUnableToShipTo());
//        var_export(CountryListService::commonDuplicatedAtTop());
//        var_export(CountryListService::commonDuplicatedAtTopExcludeUnableToShipTo());
//        var_export(CountryListService::unableToShipTo());

//        $unableToShipTo = CountryListService::unableToShipTo();
//        $unableToShipTo = json_encode($unableToShipTo);
//        var_export($unableToShipTo);

//        var_export(LocationReferenceService::name($alpha2));
//        var_export(LocationReferenceService::alpha2($country));

//        var_export(LocationReferenceService::regions($alpha2));
//        var_export(LocationReferenceService::currencies($alpha2));
//        var_export(LocationReferenceService::currencies($alpha2, true));

//        var_export(LocationReferenceService::countryNamesFromOtherServices(LocationReferenceService::$services['league']));
//        var_export(LocationReferenceService::countryNamesFromOtherServices(LocationReferenceService::$services['monarobase']));
//        var_export(LocationReferenceService::countryNamesFromOtherServices(LocationReferenceService::$services['sokil']));
//        var_export(LocationReferenceService::countryNamesFromOtherServices());
```
