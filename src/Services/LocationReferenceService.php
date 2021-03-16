<?php
namespace Railroad\Location\Services;

use Monarobase\CountryList\CountryListFacade;
use League\ISO3166\ISO3166;
use Sokil\IsoCodes\Database\Countries\Country;
use Sokil\IsoCodes\IsoCodesFactory;

class LocationReferenceService
{
    public static $services = [
        'monarobase' => 'monarobase',
        'league' => 'league',
        'sokil' => 'sokil',
    ];

    public static $keys = [
        'name' => ISO3166::KEY_NAME,
        'alpha2' => ISO3166::KEY_ALPHA2,
        'alpha3' => ISO3166::KEY_ALPHA3,
        'numeric' => ISO3166::KEY_NUMERIC,
        'currency' => 'currency',
    ];

    public static function countriesMasterList()
    {
        $countries = (new ISO3166)->all();

        $namesToAlter = config('location.countries-name-altered', []);

        foreach($namesToAlter as $alpha2ToFind => $nameToUse){
            foreach($countries as &$country){
                if($country[self::$keys['alpha2']] == $alpha2ToFind){
                    $country[self::$keys['name']] = $nameToUse;
                }
            }
        }
        unset($country); // to avoid unintened behaviour from for-loop pass-by-reference, as per foreach php docs

        $countriesToAdd = config('location.user-defined', []);

        $countries = array_merge($countries, $countriesToAdd);

        return $countries;
    }

    public static function countriesListCreator($excludeUnableToShipTo = false, $copyCommonToTop = false)
    {
        $countries = self::countriesMasterList();

        foreach($countries as $country){
            $simplifiedResults[$country[self::$keys['alpha2']]] =
                $country[self::$keys['name']];
        }

        $countries = $simplifiedResults;

        if($excludeUnableToShipTo){
            $countries = array_filter(
                $countries,
                function($alpha2){
                    $UnableToShipTo = in_array($alpha2, config('location.countries-unable-to-ship-to', []));
                    return !$UnableToShipTo;
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        if($copyCommonToTop){
            // The most commonly used countries are listed at the top of the list.
            // But we still want them in the original location in case users don't see them at the top.

            $commonToCopyToTopFormatted = [];
            $commonToCopyToTop = config('location.common-at-top', []);

            foreach($commonToCopyToTop as $alpha2){
                $commonToCopyToTopFormatted[] = self::name($alpha2);
            }

            // transform to indexed array to enable duplicate countries.
            $countries = array_values($countries);

            // reverse array to add. This is preserve order after we loop through and add to array using array_unshift
            $commonToCopyToTopFormatted = array_reverse($commonToCopyToTopFormatted);

            foreach($commonToCopyToTopFormatted as $addToTop){
                // prepend countries array with value duplicating value so long as keys differ
                // which they do because indexed not associative
                array_unshift($countries, $addToTop);
            }
        }

        return $countries;
    }

    public static function alpha2($name)
    {
        $countries = self::countriesListCreator();

        return array_search($name, $countries);
    }

    public static function name($alpha2)
    {
        $countries = self::countriesListCreator();

        return $countries[$alpha2];
    }

    public function fetchDataForCountry($country)
    {
        return (new ISO3166)->name($country);
    }

    public static function currencies($alpha2, $verbose = false)
    {
        $countries = self::countriesMasterList();

        foreach($countries as $country){
            if($country['alpha2'] === $alpha2){

                /** @var array $currenciesRequested */
                $currenciesRequested = $country['currency'];
            }
        }

        if($verbose){
            return self::currenciesDetailed($currenciesRequested);
        }

        return $currenciesRequested;
    }

    private static function currenciesDetailed($currenciesRequested)
    {
        $isoCodes = new IsoCodesFactory();

        $currencies = $isoCodes->getCurrencies();
        foreach($currenciesRequested as $letterCode){
            $currencyDetails = $currencies->getByLetterCode($letterCode);

            if($currencyDetails === null){
                continue;
            }

            $currenciesDetails[$letterCode] = [
                'name' => $currencyDetails->getName(), // Czech Koruna
                'local-name' => $currencyDetails->getLocalName(), // Чеська крона
                'numeric-code' => $currencyDetails->getNumericCode(), // 203
            ];
        }

        return $currenciesDetails;
    }

    public static function regions($alpha2, $returnSimpleArray = true) // call "getSubdivisions" instead?
    {
        $regions = (new IsoCodesFactory)->getSubdivisions()->getAllByCountryCode($alpha2);

        if($returnSimpleArray){
            $simpleArrayOfRegionNames = [];
            foreach($regions as $region){
                $simpleArrayOfRegionNames[$region->getCode()] = $region->getName();
            }
            return $simpleArrayOfRegionNames;
        }

        return $regions;
    }

    public static function countryNamesFromOtherServices($service = null)
    {
        if ($service == self::$services['league']){
            return self::countriesMasterList();
        }

        if($service == self::$services['monarobase']){
            return CountryListFacade::getList('en', 'php');
        }

        if($service == self::$services['sokil']){
            /** @var Country[] $countries */
            $countries = (new IsoCodesFactory)->getCountries();

            $countriesFormatted = [];

            foreach($countries as $country){
                $name = $country->getName();
                $alpha2 = $country->getAlpha2();
                $countriesFormatted[$alpha2] = $name;
            }

            return $countriesFormatted;
        }

        return null;
    }
}
