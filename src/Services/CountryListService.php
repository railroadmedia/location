<?php
namespace Railroad\Location\Services;

class CountryListService
{
    public static function all()
    {
        return LocationReferenceService::countriesListCreator(false, false);
    }

    public static function verbose()
    {
        return LocationReferenceService::countriesMasterList();
    }

        public static function allWeCanShipTo()
    {
        return LocationReferenceService::countriesListCreator(true, false);
    }

    public static function allWithCommonDuplicatedAtTop()
    {
        return LocationReferenceService::countriesListCreator(false, true);
    }

    public static function allWeCanShipToWithCommonDuplicatedAtTop()
    {
        return LocationReferenceService::countriesListCreator(true, true);
    }

    public static function unableToShipTo()
    {
        $unableToShipTo = [];
        $unableToShipToAlpha2Only = config('location.countries-unable-to-ship-to', []);

        $allCountries = LocationReferenceService::countriesMasterList();

        foreach($unableToShipToAlpha2Only as $alpha2){
            $name = LocationReferenceService::name($alpha2);

            $unableToShipTo[$alpha2] = $name;
        }

        ksort($unableToShipTo);

        return $unableToShipTo;
    }
}
