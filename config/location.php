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

        // as per the "ePost Global (PPDC, PPT)" column at https://www.epostglobalshipping.com/imp-serviceupdates.html
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
        'BO', # Bolivia (Plurinational State of)    referred to as "Bolivia" at epostglobalshipping.com
        'BN', # Brunei Darussalam                   referred to as "Brunei" at epostglobalshipping.com
        'SS', # South Sudan                         referred to as "Sudan(South)" at epostglobalshipping.com
        'SY', # Syrian Arab Republic (the)          referred to as "Syria (SAR)" at epostglobalshipping.com

        // as per USPS list of sanctioned countries at https://about.usps.com/publications/pub699/pub699_online_017.htm
        'CU', # Cuba
        'IR', # Iran (Islamic Republic of)
        'KP', # Korea (the Democratic People's Republic of)
        'SD', # Sudan (the)

        // as per https://about.usps.com/newsroom/service-alerts/international/welcome.htm
        'BW', # Botswana
        'GF', # French Guiana
        'MU', # Mauritius
        'SC', # Seychelles
        'PF', # French Polynesia
        'MN', # Mongolia
        'SS', # South Sudan
        'TD', # Chad
        'GP', # Guadeloupe
        'MM', # Myanmar
        'TJ', # Tajikistan
        'CU', # Cuba
        'TL', # Timor-Leste
        'ER', # Eritrea
        'LS', # Lesotho
        'TM', # Turkmenistan
        'MQ', # Martinique
        'WS', # Samoa
        'YE', # Yemen
        'LA', # Lao People's Democratic Republic    # refered to as "Laos" at USPS source
        'RE', # Réunion                             # refered to as "Reunion (Bourbon)" at USPS source
        'PM', # Saint Pierre and Miquelon           # refered to as "Saint Pierre and Miquelon (Miquelon)" at USPS source
        'SZ', # Eswatini                            # refered to as "Eswatini (Swaziland)" at USPS source
    ],

    // If we need to prohibit physical orders by region, we'll need this.
//    'regions-no-shipping-by-country' => [
//        'UA' => [ # Ukraine
//            'UA-43', # 'Avtonomna Respublika Krym', as per USPS sanctions list
//        ],
//    ],

    'countries-name-altered' => [
        'TW' => 'Taiwan',
        'US' => 'United States',
        'GB' => 'United Kingdom',
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