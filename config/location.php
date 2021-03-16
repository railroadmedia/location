<?php

return [
    'disabled' => env('DISABLE_LOCATION', false),
    'environment' => 'development',
    'testing_ip' => '70.69.219.138',
    'active_api' => 'ip-api.com',
    'api' => [
        'ipdata.co' => [
            'url' => 'https://api.ipdata.co/',
            'apiKey' => env('IP_DATA_API_KEY'),
            'countryKey' => 'country_name',
            'countryCodeKey' => 'country_code',
            'regionNameKey' => 'region_name',
            'latitudeKey' => 'latitude',
            'longitudeKey' => 'longitude',
        ],
        'freegeoip.net' => [
            'url' => 'http://freegeoip.net/json/',
            'countryKey' => 'country_name',
            'countryCodeKey' => 'country_code',
            'regionNameKey' => 'region_name',
            'latitudeKey' => 'latitude',
            'longitudeKey' => 'longitude',
        ],
        'ip-api.com' => [
            'url' => 'http://ip-api.com/json/',
            'countryKey' => 'country',
            'countryCodeKey' => 'countryCode',
            'regionNameKey' => 'regionName',
            'latitudeKey' => 'lat',
            'longitudeKey' => 'lon',
            'cityKey' => 'city',
        ],
    ],

    'countries-unable-to-ship-to' => [ // check for updates at epostglobalshipping.com/imp-serviceupdates.html
        'BM', # Bermuda
        'BT', # Bhutan
        'BW', # Botswana
        'KY', # Cayman Islands
        'DM', # Dominica
        'DO', # Dominican Republic
        'LY', # Libya
        'PG', # Papua New Guinea
        'TJ', # Tajikistan
        'YE', # Yemen
        'ZW', # Zimbabwe
        'BO', # Bolivia (Plurinational State of)    referred to as "Bolivia" at shipping status source
        'BN', # Brunei Darussalam                   referred to as "Brunei" at shipping status source
        'SS', # South Sudan                         referred to as "Sudan(South)" at shipping status source
        'SY', # Syrian Arab Republic (the)          referred to as "Syria (SAR)" at shipping status source
    ],

    'countries-name-altered' => [
        'TW' => 'Taiwan'
    ],

    'common-at-top' => [
        'US',
        'CA',
        'GB',
        'AU',
    ],

    /*
     * Non-standard user-defined entries
     * ---------------------------------
     *
     * If a country is not represented in ISO 3166, we can add it here. It the same values cannot be assumed to carry
     * the same designation outside of the scope of this configuration.
     *
     * For example, while XK is used by organizations to represent Kosovo, it is not standardized and thus cannot be
     * guarenteed to have to same meaning elsewhere.
     *
     * "User-assigned code elements are codes at the disposal of users who need to add further names of countries,
     * territories, or other geographical entities to their in-house application of ISO 3166-1, and the ISO 3166/MA will
     * never use these codes in the updating process of the standard. The following alpha-2 codes can be user-assigned:
     * AA, QM to QZ, XA to XZ, and ZZ."
     * source: https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#User-assigned_code_elements, March 12th 2021
     * Also see https://en.wikipedia.org/wiki/ISO_3166-1_numeric#User-assigned_code_elements regarding numerics)
     */
    'user-defined' => [
        [
            'name' => 'Kosovo',
            'alpha2' => 'XK',
            'alpha3' => 'XKS',
            'numeric' => 900,
            'currency' => ['EUR'],
        ]
    ]
];