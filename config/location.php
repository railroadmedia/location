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

    'countries-unable-to-ship-to' => [ // See README for instructions on updating this list.
        'AF', # Afghanistan
        'BN', # Brunei Darussalam
        'BT', # Bhutan
        'CU', # Cuba
        'EC', # Ecuador
        'GN', # Guinea
        'GP', # Guadeloupe
        'HN', # Honduras
        'IR', # Iran (Islamic Republic of)
        'KP', # Korea (Democratic People's Republic of)
        'KY', # Cayman Islands
        'KZ', # Kazakhstan
        'LA', # Lao People's Democratic Republic
        'LY', # Libya
        'MQ', # Martinique
        'NI', # Nicaragua
        'NR', # Nauru
        'PG', # Papua New Guinea
        'RU', # Russian Federation
        'SB', # Solomon Islands
        'SD', # Sudan
        'SS', # South Sudan
        'SY', # Syrian Arab Republic
        'TJ', # Tajikistan
        'TL', # Timor-Leste
        'TM', # Turkmenistan
        'UA', # Ukraine
        'YE', # Yemen
    ],

    // If we need to prohibit physical orders by region, we'll need this.
//    'regions-no-shipping-by-country' => [
//        'UA' => [ # Ukraine
//            'UA-43', # 'Avtonomna Respublika Krym', as per USPS sanctions list
//        ],
//    ],

    'countries-name-altered' => [
        'TW' => 'Taiwan',           # renamed from "Taiwan (Province of China)"
        'US' => 'United States',    # renamed from "United States of America (the)"
        'GB' => 'United Kingdom',   # renamed from "United Kingdom of Great Britain and Northern Ireland (the)"
    ],

    'common-at-top' => [ // for usage example, see CountryListService::allWithCommonDuplicatedAtTop()
        'US', # United States of America (the)
        'CA', # Canada
        'GB', # United Kingdom of Great Britain and Northern Ireland (the)
        'AU', # Austrailia
    ],

    /*
     * Non-standard user-defined entries
     * ---------------------------------
     *
     * If a country is not represented in ISO 3166, we can add it here. However remember that is not standardized and
     * thus cannot be assumed to have to same meaning elsewhere. For example, "XK" is used by many—but not
     * all—organizations to represent Kosovo.
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