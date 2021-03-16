railroad\/location
========================

Instructions
---------------

Install, publish vendor, check & modify application's config/app.php file, and package's installed config/location.php file as needed.


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

Configuration
---------------

artisan vendor:publish will publish a location.php file to your application's config/ directory. In there you can specify values to override these default package values:

```
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
```

Use
-------------

### TL;DR:


These will get various lists countries:

* CountryListService::all()
* CountryListService::verbose()
* CountryListService::allWeCanShipTo()
* CountryListService::allWithCommonDuplicatedAtTop()
* CountryListService::allWeCanShipToWithCommonDuplicatedAtTop()
* CountryListService::unableToShipTo()

These are handy:

* LocationReferenceService::alpha2($name)
* LocationReferenceService::name($alpha2)
* LocationReferenceService::currencies($alpha2, $verbose = false)
* LocationReferenceService::regions($alpha2, $returnSimpleArray = true) // call "getSubdivisions" instead?

These are advanced. Pay no attention to the man behind the curtain. Can be useful for extension though:

* LocationReferenceService::countriesMasterList()
* LocationReferenceService::countriesListCreator($allWeCanShipTo = false, $copyCommonToTop = false)
* LocationReferenceService::countryNamesFromOtherServices($service = null)


### Detailed


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

Also note that for all examples below, the config('location') is configured with this information:

```
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
```


#### Lists of countries

##### CountryListService::all()

returns something like the following:

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
  'AR' => 'Argentina',
  'AM' => 'Armenia',
  'AW' => 'Aruba',
  'AU' => 'Australia',
  'AT' => 'Austria',
  'AZ' => 'Azerbaijan',
  'BS' => 'Bahamas',
  'BH' => 'Bahrain',
  'BD' => 'Bangladesh',
  'BB' => 'Barbados',
  'BY' => 'Belarus',
  'BE' => 'Belgium',
  'BZ' => 'Belize',
  'BJ' => 'Benin',
  'BM' => 'Bermuda',
  'BT' => 'Bhutan',
  'BO' => 'Bolivia (Plurinational State of)',
  'BQ' => 'Bonaire, Sint Eustatius and Saba',
  'BA' => 'Bosnia and Herzegovina',
  'BW' => 'Botswana',
  'BV' => 'Bouvet Island',
  'BR' => 'Brazil',
  'IO' => 'British Indian Ocean Territory',
  'BN' => 'Brunei Darussalam',
  'BG' => 'Bulgaria',
  'BF' => 'Burkina Faso',
  'BI' => 'Burundi',
  'CV' => 'Cabo Verde',
  'KH' => 'Cambodia',
  'CM' => 'Cameroon',
  'CA' => 'Canada',
  'KY' => 'Cayman Islands',
  'CF' => 'Central African Republic',
  'TD' => 'Chad',
  'CL' => 'Chile',
  'CN' => 'China',
  'CX' => 'Christmas Island',
  'CC' => 'Cocos (Keeling) Islands',
  'CO' => 'Colombia',
  'KM' => 'Comoros',
  'CG' => 'Congo',
  'CD' => 'Congo (Democratic Republic of the)',
  'CK' => 'Cook Islands',
  'CR' => 'Costa Rica',
  'CI' => 'Côte d\'Ivoire',
  'HR' => 'Croatia',
  'CU' => 'Cuba',
  'CW' => 'Curaçao',
  'CY' => 'Cyprus',
  'CZ' => 'Czechia',
  'DK' => 'Denmark',
  'DJ' => 'Djibouti',
  'DM' => 'Dominica',
  'DO' => 'Dominican Republic',
  'EC' => 'Ecuador',
  'EG' => 'Egypt',
  'SV' => 'El Salvador',
  'GQ' => 'Equatorial Guinea',
  'ER' => 'Eritrea',
  'EE' => 'Estonia',
  'ET' => 'Ethiopia',
  'SZ' => 'Eswatini',
  'FK' => 'Falkland Islands (Malvinas)',
  'FO' => 'Faroe Islands',
  'FJ' => 'Fiji',
  'FI' => 'Finland',
  'FR' => 'France',
  'GF' => 'French Guiana',
  'PF' => 'French Polynesia',
  'TF' => 'French Southern Territories',
  'GA' => 'Gabon',
  'GM' => 'Gambia',
  'GE' => 'Georgia',
  'DE' => 'Germany',
  'GH' => 'Ghana',
  'GI' => 'Gibraltar',
  'GR' => 'Greece',
  'GL' => 'Greenland',
  'GD' => 'Grenada',
  'GP' => 'Guadeloupe',
  'GU' => 'Guam',
  'GT' => 'Guatemala',
  'GG' => 'Guernsey',
  'GN' => 'Guinea',
  'GW' => 'Guinea-Bissau',
  'GY' => 'Guyana',
  'HT' => 'Haiti',
  'HM' => 'Heard Island and McDonald Islands',
  'VA' => 'Holy See',
  'HN' => 'Honduras',
  'HK' => 'Hong Kong',
  'HU' => 'Hungary',
  'IS' => 'Iceland',
  'IN' => 'India',
  'ID' => 'Indonesia',
  'IR' => 'Iran (Islamic Republic of)',
  'IQ' => 'Iraq',
  'IE' => 'Ireland',
  'IM' => 'Isle of Man',
  'IL' => 'Israel',
  'IT' => 'Italy',
  'JM' => 'Jamaica',
  'JP' => 'Japan',
  'JE' => 'Jersey',
  'JO' => 'Jordan',
  'KZ' => 'Kazakhstan',
  'KE' => 'Kenya',
  'KI' => 'Kiribati',
  'KP' => 'Korea (Democratic People\'s Republic of)',
  'KR' => 'Korea (Republic of)',
  'KW' => 'Kuwait',
  'KG' => 'Kyrgyzstan',
  'LA' => 'Lao People\'s Democratic Republic',
  'LV' => 'Latvia',
  'LB' => 'Lebanon',
  'LS' => 'Lesotho',
  'LR' => 'Liberia',
  'LY' => 'Libya',
  'LI' => 'Liechtenstein',
  'LT' => 'Lithuania',
  'LU' => 'Luxembourg',
  'MO' => 'Macao',
  'MK' => 'North Macedonia',
  'MG' => 'Madagascar',
  'MW' => 'Malawi',
  'MY' => 'Malaysia',
  'MV' => 'Maldives',
  'ML' => 'Mali',
  'MT' => 'Malta',
  'MH' => 'Marshall Islands',
  'MQ' => 'Martinique',
  'MR' => 'Mauritania',
  'MU' => 'Mauritius',
  'YT' => 'Mayotte',
  'MX' => 'Mexico',
  'FM' => 'Micronesia (Federated States of)',
  'MD' => 'Moldova (Republic of)',
  'MC' => 'Monaco',
  'MN' => 'Mongolia',
  'ME' => 'Montenegro',
  'MS' => 'Montserrat',
  'MA' => 'Morocco',
  'MZ' => 'Mozambique',
  'MM' => 'Myanmar',
  'NA' => 'Namibia',
  'NR' => 'Nauru',
  'NP' => 'Nepal',
  'NL' => 'Netherlands',
  'NC' => 'New Caledonia',
  'NZ' => 'New Zealand',
  'NI' => 'Nicaragua',
  'NE' => 'Niger',
  'NG' => 'Nigeria',
  'NU' => 'Niue',
  'NF' => 'Norfolk Island',
  'MP' => 'Northern Mariana Islands',
  'NO' => 'Norway',
  'OM' => 'Oman',
  'PK' => 'Pakistan',
  'PW' => 'Palau',
  'PS' => 'Palestine, State of',
  'PA' => 'Panama',
  'PG' => 'Papua New Guinea',
  'PY' => 'Paraguay',
  'PE' => 'Peru',
  'PH' => 'Philippines',
  'PN' => 'Pitcairn',
  'PL' => 'Poland',
  'PT' => 'Portugal',
  'PR' => 'Puerto Rico',
  'QA' => 'Qatar',
  'RE' => 'Réunion',
  'RO' => 'Romania',
  'RU' => 'Russian Federation',
  'RW' => 'Rwanda',
  'BL' => 'Saint Barthélemy',
  'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
  'KN' => 'Saint Kitts and Nevis',
  'LC' => 'Saint Lucia',
  'MF' => 'Saint Martin (French part)',
  'PM' => 'Saint Pierre and Miquelon',
  'VC' => 'Saint Vincent and the Grenadines',
  'WS' => 'Samoa',
  'SM' => 'San Marino',
  'ST' => 'Sao Tome and Principe',
  'SA' => 'Saudi Arabia',
  'SN' => 'Senegal',
  'RS' => 'Serbia',
  'SC' => 'Seychelles',
  'SL' => 'Sierra Leone',
  'SG' => 'Singapore',
  'SX' => 'Sint Maarten (Dutch part)',
  'SK' => 'Slovakia',
  'SI' => 'Slovenia',
  'SB' => 'Solomon Islands',
  'SO' => 'Somalia',
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
  'TH' => 'Thailand',
  'TL' => 'Timor-Leste',
  'TG' => 'Togo',
  'TK' => 'Tokelau',
  'TO' => 'Tonga',
  'TT' => 'Trinidad and Tobago',
  'TN' => 'Tunisia',
  'TR' => 'Turkey',
  'TM' => 'Turkmenistan',
  'TC' => 'Turks and Caicos Islands',
  'TV' => 'Tuvalu',
  'UG' => 'Uganda',
  'UA' => 'Ukraine',
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
  'YE' => 'Yemen',
  'ZM' => 'Zambia',
  'ZW' => 'Zimbabwe',
  'XK' => 'Kosovo',
)
```

##### CountryListService::verbose()

returns something like the following:

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
  # ... for brevity many countries omitted from this example
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


##### CountryListService::allWeCanShipTo()

returns something like the following:

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
  'AR' => 'Argentina',
  'AM' => 'Armenia',
  'AW' => 'Aruba',
  'AU' => 'Australia',
  'AT' => 'Austria',
  'AZ' => 'Azerbaijan',
  'BS' => 'Bahamas',
  'BH' => 'Bahrain',
  'BD' => 'Bangladesh',
  'BB' => 'Barbados',
  'BY' => 'Belarus',
  'BE' => 'Belgium',
  'BZ' => 'Belize',
  'BJ' => 'Benin',
  'BQ' => 'Bonaire, Sint Eustatius and Saba',
  'BA' => 'Bosnia and Herzegovina',
  'BV' => 'Bouvet Island',
  'BR' => 'Brazil',
  'IO' => 'British Indian Ocean Territory',
  'BG' => 'Bulgaria',
  'BF' => 'Burkina Faso',
  'BI' => 'Burundi',
  'CV' => 'Cabo Verde',
  'KH' => 'Cambodia',
  'CM' => 'Cameroon',
  'CA' => 'Canada',
  'CF' => 'Central African Republic',
  'TD' => 'Chad',
  'CL' => 'Chile',
  'CN' => 'China',
  'CX' => 'Christmas Island',
  'CC' => 'Cocos (Keeling) Islands',
  'CO' => 'Colombia',
  'KM' => 'Comoros',
  'CG' => 'Congo',
  'CD' => 'Congo (Democratic Republic of the)',
  'CK' => 'Cook Islands',
  'CR' => 'Costa Rica',
  'CI' => 'Côte d\'Ivoire',
  'HR' => 'Croatia',
  'CU' => 'Cuba',
  'CW' => 'Curaçao',
  'CY' => 'Cyprus',
  'CZ' => 'Czechia',
  'DK' => 'Denmark',
  'DJ' => 'Djibouti',
  'EC' => 'Ecuador',
  'EG' => 'Egypt',
  'SV' => 'El Salvador',
  'GQ' => 'Equatorial Guinea',
  'ER' => 'Eritrea',
  'EE' => 'Estonia',
  'ET' => 'Ethiopia',
  'SZ' => 'Eswatini',
  'FK' => 'Falkland Islands (Malvinas)',
  'FO' => 'Faroe Islands',
  'FJ' => 'Fiji',
  'FI' => 'Finland',
  'FR' => 'France',
  'GF' => 'French Guiana',
  'PF' => 'French Polynesia',
  'TF' => 'French Southern Territories',
  'GA' => 'Gabon',
  'GM' => 'Gambia',
  'GE' => 'Georgia',
  'DE' => 'Germany',
  'GH' => 'Ghana',
  'GI' => 'Gibraltar',
  'GR' => 'Greece',
  'GL' => 'Greenland',
  'GD' => 'Grenada',
  'GP' => 'Guadeloupe',
  'GU' => 'Guam',
  'GT' => 'Guatemala',
  'GG' => 'Guernsey',
  'GN' => 'Guinea',
  'GW' => 'Guinea-Bissau',
  'GY' => 'Guyana',
  'HT' => 'Haiti',
  'HM' => 'Heard Island and McDonald Islands',
  'VA' => 'Holy See',
  'HN' => 'Honduras',
  'HK' => 'Hong Kong',
  'HU' => 'Hungary',
  'IS' => 'Iceland',
  'IN' => 'India',
  'ID' => 'Indonesia',
  'IR' => 'Iran (Islamic Republic of)',
  'IQ' => 'Iraq',
  'IE' => 'Ireland',
  'IM' => 'Isle of Man',
  'IL' => 'Israel',
  'IT' => 'Italy',
  'JM' => 'Jamaica',
  'JP' => 'Japan',
  'JE' => 'Jersey',
  'JO' => 'Jordan',
  'KZ' => 'Kazakhstan',
  'KE' => 'Kenya',
  'KI' => 'Kiribati',
  'KP' => 'Korea (Democratic People\'s Republic of)',
  'KR' => 'Korea (Republic of)',
  'KW' => 'Kuwait',
  'KG' => 'Kyrgyzstan',
  'LA' => 'Lao People\'s Democratic Republic',
  'LV' => 'Latvia',
  'LB' => 'Lebanon',
  'LS' => 'Lesotho',
  'LR' => 'Liberia',
  'LI' => 'Liechtenstein',
  'LT' => 'Lithuania',
  'LU' => 'Luxembourg',
  'MO' => 'Macao',
  'MK' => 'North Macedonia',
  'MG' => 'Madagascar',
  'MW' => 'Malawi',
  'MY' => 'Malaysia',
  'MV' => 'Maldives',
  'ML' => 'Mali',
  'MT' => 'Malta',
  'MH' => 'Marshall Islands',
  'MQ' => 'Martinique',
  'MR' => 'Mauritania',
  'MU' => 'Mauritius',
  'YT' => 'Mayotte',
  'MX' => 'Mexico',
  'FM' => 'Micronesia (Federated States of)',
  'MD' => 'Moldova (Republic of)',
  'MC' => 'Monaco',
  'MN' => 'Mongolia',
  'ME' => 'Montenegro',
  'MS' => 'Montserrat',
  'MA' => 'Morocco',
  'MZ' => 'Mozambique',
  'MM' => 'Myanmar',
  'NA' => 'Namibia',
  'NR' => 'Nauru',
  'NP' => 'Nepal',
  'NL' => 'Netherlands',
  'NC' => 'New Caledonia',
  'NZ' => 'New Zealand',
  'NI' => 'Nicaragua',
  'NE' => 'Niger',
  'NG' => 'Nigeria',
  'NU' => 'Niue',
  'NF' => 'Norfolk Island',
  'MP' => 'Northern Mariana Islands',
  'NO' => 'Norway',
  'OM' => 'Oman',
  'PK' => 'Pakistan',
  'PW' => 'Palau',
  'PS' => 'Palestine, State of',
  'PA' => 'Panama',
  'PY' => 'Paraguay',
  'PE' => 'Peru',
  'PH' => 'Philippines',
  'PN' => 'Pitcairn',
  'PL' => 'Poland',
  'PT' => 'Portugal',
  'PR' => 'Puerto Rico',
  'QA' => 'Qatar',
  'RE' => 'Réunion',
  'RO' => 'Romania',
  'RU' => 'Russian Federation',
  'RW' => 'Rwanda',
  'BL' => 'Saint Barthélemy',
  'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
  'KN' => 'Saint Kitts and Nevis',
  'LC' => 'Saint Lucia',
  'MF' => 'Saint Martin (French part)',
  'PM' => 'Saint Pierre and Miquelon',
  'VC' => 'Saint Vincent and the Grenadines',
  'WS' => 'Samoa',
  'SM' => 'San Marino',
  'ST' => 'Sao Tome and Principe',
  'SA' => 'Saudi Arabia',
  'SN' => 'Senegal',
  'RS' => 'Serbia',
  'SC' => 'Seychelles',
  'SL' => 'Sierra Leone',
  'SG' => 'Singapore',
  'SX' => 'Sint Maarten (Dutch part)',
  'SK' => 'Slovakia',
  'SI' => 'Slovenia',
  'SB' => 'Solomon Islands',
  'SO' => 'Somalia',
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
  'TH' => 'Thailand',
  'TL' => 'Timor-Leste',
  'TG' => 'Togo',
  'TK' => 'Tokelau',
  'TO' => 'Tonga',
  'TT' => 'Trinidad and Tobago',
  'TN' => 'Tunisia',
  'TR' => 'Turkey',
  'TM' => 'Turkmenistan',
  'TC' => 'Turks and Caicos Islands',
  'TV' => 'Tuvalu',
  'UG' => 'Uganda',
  'UA' => 'Ukraine',
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

Note the countries defined in the above noted config are missing from that list.


##### CountryListService::allWithCommonDuplicatedAtTop()

returns something like the following:

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
  14 => 'Argentina',
  15 => 'Armenia',
  16 => 'Aruba',
  17 => 'Australia',
  18 => 'Austria',
  19 => 'Azerbaijan',
  20 => 'Bahamas',
  21 => 'Bahrain',
  22 => 'Bangladesh',
  23 => 'Barbados',
  24 => 'Belarus',
  25 => 'Belgium',
  26 => 'Belize',
  27 => 'Benin',
  28 => 'Bermuda',
  29 => 'Bhutan',
  30 => 'Bolivia (Plurinational State of)',
  31 => 'Bonaire, Sint Eustatius and Saba',
  32 => 'Bosnia and Herzegovina',
  33 => 'Botswana',
  34 => 'Bouvet Island',
  35 => 'Brazil',
  36 => 'British Indian Ocean Territory',
  37 => 'Brunei Darussalam',
  38 => 'Bulgaria',
  39 => 'Burkina Faso',
  40 => 'Burundi',
  41 => 'Cabo Verde',
  42 => 'Cambodia',
  43 => 'Cameroon',
  44 => 'Canada',
  45 => 'Cayman Islands',
  46 => 'Central African Republic',
  47 => 'Chad',
  48 => 'Chile',
  49 => 'China',
  50 => 'Christmas Island',
  51 => 'Cocos (Keeling) Islands',
  52 => 'Colombia',
  53 => 'Comoros',
  54 => 'Congo',
  55 => 'Congo (Democratic Republic of the)',
  56 => 'Cook Islands',
  57 => 'Costa Rica',
  58 => 'Côte d\'Ivoire',
  59 => 'Croatia',
  60 => 'Cuba',
  61 => 'Curaçao',
  62 => 'Cyprus',
  63 => 'Czechia',
  64 => 'Denmark',
  65 => 'Djibouti',
  66 => 'Dominica',
  67 => 'Dominican Republic',
  68 => 'Ecuador',
  69 => 'Egypt',
  70 => 'El Salvador',
  71 => 'Equatorial Guinea',
  72 => 'Eritrea',
  73 => 'Estonia',
  74 => 'Ethiopia',
  75 => 'Eswatini',
  76 => 'Falkland Islands (Malvinas)',
  77 => 'Faroe Islands',
  78 => 'Fiji',
  79 => 'Finland',
  80 => 'France',
  81 => 'French Guiana',
  82 => 'French Polynesia',
  83 => 'French Southern Territories',
  84 => 'Gabon',
  85 => 'Gambia',
  86 => 'Georgia',
  87 => 'Germany',
  88 => 'Ghana',
  89 => 'Gibraltar',
  90 => 'Greece',
  91 => 'Greenland',
  92 => 'Grenada',
  93 => 'Guadeloupe',
  94 => 'Guam',
  95 => 'Guatemala',
  96 => 'Guernsey',
  97 => 'Guinea',
  98 => 'Guinea-Bissau',
  99 => 'Guyana',
  100 => 'Haiti',
  101 => 'Heard Island and McDonald Islands',
  102 => 'Holy See',
  103 => 'Honduras',
  104 => 'Hong Kong',
  105 => 'Hungary',
  106 => 'Iceland',
  107 => 'India',
  108 => 'Indonesia',
  109 => 'Iran (Islamic Republic of)',
  110 => 'Iraq',
  111 => 'Ireland',
  112 => 'Isle of Man',
  113 => 'Israel',
  114 => 'Italy',
  115 => 'Jamaica',
  116 => 'Japan',
  117 => 'Jersey',
  118 => 'Jordan',
  119 => 'Kazakhstan',
  120 => 'Kenya',
  121 => 'Kiribati',
  122 => 'Korea (Democratic People\'s Republic of)',
  123 => 'Korea (Republic of)',
  124 => 'Kuwait',
  125 => 'Kyrgyzstan',
  126 => 'Lao People\'s Democratic Republic',
  127 => 'Latvia',
  128 => 'Lebanon',
  129 => 'Lesotho',
  130 => 'Liberia',
  131 => 'Libya',
  132 => 'Liechtenstein',
  133 => 'Lithuania',
  134 => 'Luxembourg',
  135 => 'Macao',
  136 => 'North Macedonia',
  137 => 'Madagascar',
  138 => 'Malawi',
  139 => 'Malaysia',
  140 => 'Maldives',
  141 => 'Mali',
  142 => 'Malta',
  143 => 'Marshall Islands',
  144 => 'Martinique',
  145 => 'Mauritania',
  146 => 'Mauritius',
  147 => 'Mayotte',
  148 => 'Mexico',
  149 => 'Micronesia (Federated States of)',
  150 => 'Moldova (Republic of)',
  151 => 'Monaco',
  152 => 'Mongolia',
  153 => 'Montenegro',
  154 => 'Montserrat',
  155 => 'Morocco',
  156 => 'Mozambique',
  157 => 'Myanmar',
  158 => 'Namibia',
  159 => 'Nauru',
  160 => 'Nepal',
  161 => 'Netherlands',
  162 => 'New Caledonia',
  163 => 'New Zealand',
  164 => 'Nicaragua',
  165 => 'Niger',
  166 => 'Nigeria',
  167 => 'Niue',
  168 => 'Norfolk Island',
  169 => 'Northern Mariana Islands',
  170 => 'Norway',
  171 => 'Oman',
  172 => 'Pakistan',
  173 => 'Palau',
  174 => 'Palestine, State of',
  175 => 'Panama',
  176 => 'Papua New Guinea',
  177 => 'Paraguay',
  178 => 'Peru',
  179 => 'Philippines',
  180 => 'Pitcairn',
  181 => 'Poland',
  182 => 'Portugal',
  183 => 'Puerto Rico',
  184 => 'Qatar',
  185 => 'Réunion',
  186 => 'Romania',
  187 => 'Russian Federation',
  188 => 'Rwanda',
  189 => 'Saint Barthélemy',
  190 => 'Saint Helena, Ascension and Tristan da Cunha',
  191 => 'Saint Kitts and Nevis',
  192 => 'Saint Lucia',
  193 => 'Saint Martin (French part)',
  194 => 'Saint Pierre and Miquelon',
  195 => 'Saint Vincent and the Grenadines',
  196 => 'Samoa',
  197 => 'San Marino',
  198 => 'Sao Tome and Principe',
  199 => 'Saudi Arabia',
  200 => 'Senegal',
  201 => 'Serbia',
  202 => 'Seychelles',
  203 => 'Sierra Leone',
  204 => 'Singapore',
  205 => 'Sint Maarten (Dutch part)',
  206 => 'Slovakia',
  207 => 'Slovenia',
  208 => 'Solomon Islands',
  209 => 'Somalia',
  210 => 'South Africa',
  211 => 'South Georgia and the South Sandwich Islands',
  212 => 'South Sudan',
  213 => 'Spain',
  214 => 'Sri Lanka',
  215 => 'Sudan',
  216 => 'Suriname',
  217 => 'Svalbard and Jan Mayen',
  218 => 'Sweden',
  219 => 'Switzerland',
  220 => 'Syrian Arab Republic',
  221 => 'Taiwan',
  222 => 'Tajikistan',
  223 => 'Tanzania, United Republic of',
  224 => 'Thailand',
  225 => 'Timor-Leste',
  226 => 'Togo',
  227 => 'Tokelau',
  228 => 'Tonga',
  229 => 'Trinidad and Tobago',
  230 => 'Tunisia',
  231 => 'Turkey',
  232 => 'Turkmenistan',
  233 => 'Turks and Caicos Islands',
  234 => 'Tuvalu',
  235 => 'Uganda',
  236 => 'Ukraine',
  237 => 'United Arab Emirates',
  238 => 'United Kingdom of Great Britain and Northern Ireland',
  239 => 'United States of America',
  240 => 'United States Minor Outlying Islands',
  241 => 'Uruguay',
  242 => 'Uzbekistan',
  243 => 'Vanuatu',
  244 => 'Venezuela (Bolivarian Republic of)',
  245 => 'Viet Nam',
  246 => 'Virgin Islands (British)',
  247 => 'Virgin Islands (U.S.)',
  248 => 'Wallis and Futuna',
  249 => 'Western Sahara',
  250 => 'Yemen',
  251 => 'Zambia',
  252 => 'Zimbabwe',
  253 => 'Kosovo',
)
```

Note the countries defined in the above noted config are *duplicated* at the top of that array.


##### CountryListService::allWeCanShipToWithCommonDuplicatedAtTop()

returns something like the following:

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
  14 => 'Argentina',
  15 => 'Armenia',
  16 => 'Aruba',
  17 => 'Australia',
  18 => 'Austria',
  19 => 'Azerbaijan',
  20 => 'Bahamas',
  21 => 'Bahrain',
  22 => 'Bangladesh',
  23 => 'Barbados',
  24 => 'Belarus',
  25 => 'Belgium',
  26 => 'Belize',
  27 => 'Benin',
  28 => 'Bonaire, Sint Eustatius and Saba',
  29 => 'Bosnia and Herzegovina',
  30 => 'Bouvet Island',
  31 => 'Brazil',
  32 => 'British Indian Ocean Territory',
  33 => 'Bulgaria',
  34 => 'Burkina Faso',
  35 => 'Burundi',
  36 => 'Cabo Verde',
  37 => 'Cambodia',
  38 => 'Cameroon',
  39 => 'Canada',
  40 => 'Central African Republic',
  41 => 'Chad',
  42 => 'Chile',
  43 => 'China',
  44 => 'Christmas Island',
  45 => 'Cocos (Keeling) Islands',
  46 => 'Colombia',
  47 => 'Comoros',
  48 => 'Congo',
  49 => 'Congo (Democratic Republic of the)',
  50 => 'Cook Islands',
  51 => 'Costa Rica',
  52 => 'Côte d\'Ivoire',
  53 => 'Croatia',
  54 => 'Cuba',
  55 => 'Curaçao',
  56 => 'Cyprus',
  57 => 'Czechia',
  58 => 'Denmark',
  59 => 'Djibouti',
  60 => 'Ecuador',
  61 => 'Egypt',
  62 => 'El Salvador',
  63 => 'Equatorial Guinea',
  64 => 'Eritrea',
  65 => 'Estonia',
  66 => 'Ethiopia',
  67 => 'Eswatini',
  68 => 'Falkland Islands (Malvinas)',
  69 => 'Faroe Islands',
  70 => 'Fiji',
  71 => 'Finland',
  72 => 'France',
  73 => 'French Guiana',
  74 => 'French Polynesia',
  75 => 'French Southern Territories',
  76 => 'Gabon',
  77 => 'Gambia',
  78 => 'Georgia',
  79 => 'Germany',
  80 => 'Ghana',
  81 => 'Gibraltar',
  82 => 'Greece',
  83 => 'Greenland',
  84 => 'Grenada',
  85 => 'Guadeloupe',
  86 => 'Guam',
  87 => 'Guatemala',
  88 => 'Guernsey',
  89 => 'Guinea',
  90 => 'Guinea-Bissau',
  91 => 'Guyana',
  92 => 'Haiti',
  93 => 'Heard Island and McDonald Islands',
  94 => 'Holy See',
  95 => 'Honduras',
  96 => 'Hong Kong',
  97 => 'Hungary',
  98 => 'Iceland',
  99 => 'India',
  100 => 'Indonesia',
  101 => 'Iran (Islamic Republic of)',
  102 => 'Iraq',
  103 => 'Ireland',
  104 => 'Isle of Man',
  105 => 'Israel',
  106 => 'Italy',
  107 => 'Jamaica',
  108 => 'Japan',
  109 => 'Jersey',
  110 => 'Jordan',
  111 => 'Kazakhstan',
  112 => 'Kenya',
  113 => 'Kiribati',
  114 => 'Korea (Democratic People\'s Republic of)',
  115 => 'Korea (Republic of)',
  116 => 'Kuwait',
  117 => 'Kyrgyzstan',
  118 => 'Lao People\'s Democratic Republic',
  119 => 'Latvia',
  120 => 'Lebanon',
  121 => 'Lesotho',
  122 => 'Liberia',
  123 => 'Liechtenstein',
  124 => 'Lithuania',
  125 => 'Luxembourg',
  126 => 'Macao',
  127 => 'North Macedonia',
  128 => 'Madagascar',
  129 => 'Malawi',
  130 => 'Malaysia',
  131 => 'Maldives',
  132 => 'Mali',
  133 => 'Malta',
  134 => 'Marshall Islands',
  135 => 'Martinique',
  136 => 'Mauritania',
  137 => 'Mauritius',
  138 => 'Mayotte',
  139 => 'Mexico',
  140 => 'Micronesia (Federated States of)',
  141 => 'Moldova (Republic of)',
  142 => 'Monaco',
  143 => 'Mongolia',
  144 => 'Montenegro',
  145 => 'Montserrat',
  146 => 'Morocco',
  147 => 'Mozambique',
  148 => 'Myanmar',
  149 => 'Namibia',
  150 => 'Nauru',
  151 => 'Nepal',
  152 => 'Netherlands',
  153 => 'New Caledonia',
  154 => 'New Zealand',
  155 => 'Nicaragua',
  156 => 'Niger',
  157 => 'Nigeria',
  158 => 'Niue',
  159 => 'Norfolk Island',
  160 => 'Northern Mariana Islands',
  161 => 'Norway',
  162 => 'Oman',
  163 => 'Pakistan',
  164 => 'Palau',
  165 => 'Palestine, State of',
  166 => 'Panama',
  167 => 'Paraguay',
  168 => 'Peru',
  169 => 'Philippines',
  170 => 'Pitcairn',
  171 => 'Poland',
  172 => 'Portugal',
  173 => 'Puerto Rico',
  174 => 'Qatar',
  175 => 'Réunion',
  176 => 'Romania',
  177 => 'Russian Federation',
  178 => 'Rwanda',
  179 => 'Saint Barthélemy',
  180 => 'Saint Helena, Ascension and Tristan da Cunha',
  181 => 'Saint Kitts and Nevis',
  182 => 'Saint Lucia',
  183 => 'Saint Martin (French part)',
  184 => 'Saint Pierre and Miquelon',
  185 => 'Saint Vincent and the Grenadines',
  186 => 'Samoa',
  187 => 'San Marino',
  188 => 'Sao Tome and Principe',
  189 => 'Saudi Arabia',
  190 => 'Senegal',
  191 => 'Serbia',
  192 => 'Seychelles',
  193 => 'Sierra Leone',
  194 => 'Singapore',
  195 => 'Sint Maarten (Dutch part)',
  196 => 'Slovakia',
  197 => 'Slovenia',
  198 => 'Solomon Islands',
  199 => 'Somalia',
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
  211 => 'Thailand',
  212 => 'Timor-Leste',
  213 => 'Togo',
  214 => 'Tokelau',
  215 => 'Tonga',
  216 => 'Trinidad and Tobago',
  217 => 'Tunisia',
  218 => 'Turkey',
  219 => 'Turkmenistan',
  220 => 'Turks and Caicos Islands',
  221 => 'Tuvalu',
  222 => 'Uganda',
  223 => 'Ukraine',
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

Note the respective countries defined in the above noted config are:

* missing from that list.
* *duplicated* at the top of that array.

##### CountryListService::unableToShipTo()

returns something like the following:

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

Note this is only the countries defined in the above noted config as unable-to-ship-to.


#### Other handy methods

##### LocationReferenceService::alpha2($name)

Given above noted configuration

* passed "Taiwan", will return "TW"
* passed "Kosovo", will return "XK"
* passed "Canada", will return "CA"
* passed "acountrythatdoesntexist", will return boolean false
* passed "Taiwan (Province of China)", will return boolean false
    * because overriden by 'alter-name' configuration


##### LocationReferenceService::name($alpha2)

* passed "TW", will return "Taiwan"
* passed "XK", will return "Kosovo"
* passed "CA", will return "Canada"
* passed "analpha2codethatdoesntexist", will return boolean false


##### LocationReferenceService::currencies($alpha2, $verbose = false)

Passed only `'CA'`, will return:

```
array (
  0 => 'CAD',
)
```

If a second argument is provided that is `true`, the result will be (assuming 1st param `'CA'`):

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

A country with several currencies, Zimbabwe (`'ZW'`):

```
array (
  0 => 'BWP',
  1 => 'EUR',
  2 => 'GBP',
  3 => 'USD',
  4 => 'ZAR',
)
```


if second param `true`:

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



##### LocationReferenceService::regions($alpha2, $returnSimpleArray = true)

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




## ***UNDER CONSTRUCTION, copy to sections above if helpful***

***UNDER CONSTRUCTION, copy to sections above if helpful***

There are several methods to get a list of countries.

For a basic list use `LocationReferenceService::CountriesAll`.

This returns an array of the "short English names" of all the countries in the ISO3166 list, indexed by their two-letter "Alpha2" country code.

It looks like this:

```
[
  'AF' => 'Afghanistan',
  'AX' => 'Åland Islands',
  'AL' => 'Albania',
  'DZ' => 'Algeria',
  'AS' => 'American Samoa',
  'AD' => 'Andorra',
  'AO' => 'Angola',
  'AI' => 'Anguilla',
  // continues...
```

If you need a list that excludes countries that we cannot ship physical orders to, then use `CountryListService::allWeCanShipTo()`.

If you want some countries to appear at the top of the list so users can find them eaiser, then use `CountryListService::allWithCommonDuplicatedAtTop()`. This will return an **indexed** array of *only the country names*.

You can define the countries that appear at the top of that list in the config value "common-at-top". The order in that array is how they will appear at the top of the list.Zimbabwe

**Note** the countries will also appear in their alphabetical location. This is for better UX in case a user didn't realize their country was at the top.

Thus, assuming that above config example, the output will look like this:

```
array (
  0 => 'United States of America',                              // 1st appearance!
  1 => 'Canada',                                                // 1st appearance!
  2 => 'United Kingdom of Great Britain and Northern Ireland',  // 1st appearance!
  3 => 'Australia',                                             // 1st appearance!
  4 => 'Afghanistan',
  5 => 'Åland Islands',
  // **shorted for readability in example**
  15 => 'Armenia',
  16 => 'Aruba',
  17 => 'Australia',                                            // 2nd appearance!
  18 => 'Austria',
  19 => 'Azerbaijan',
  // **shorted for readability in example**
  42 => 'Cambodia',
  43 => 'Cameroon',
  44 => 'Canada',                                               // 2nd appearance!
  45 => 'Cayman Islands',
  46 => 'Central African Republic',
  // **shorted for readability in example**
```

If you need that but with unable-to-ship-to countries excluded, then call `CountryListService::allWeCanShipToWithCommonDuplicatedAtTop()`.

If you need just the countries we can't ship to, call `LocationReferenceService::countriesUnableToShipTo()`

Finally, if `LocationReferenceService::verbose()` is useful, then there's that to. But, if what you need is the currencies for a particular country, you can just use the `LocationReferenceService::getCurrencies()` method described elsewhere in this document.

#### Other methods

***UNDER CONSTRUCTION, LIKELY SOME NEED UPDATING***

##### countries we cannot ship to

***UNDER CONSTRUCTION, LIKELY SOME NEED UPDATING***

For a list of those countries, call `LocationReferenceService::countriesUnableToShipTo()` to get an array of names keyed by their alpha2 (two-letter country code).LocationReferenceService

ex:

```
array (
  'BM' => 'Bermuda',
  'BT' => 'Bhutan',
  'BW' => 'Botswana',
  'KY' => 'Cayman Islands',
  'DM' => 'Dominica',
  'DO' => 'Dominican Republic',
  'LY' => 'Libya',
  'PG' => 'Papua New Guinea',
  'TJ' => 'Tajikistan',
  'YE' => 'Yemen',
  'ZW' => 'Zimbabwe',
  'BO' => 'Bolivia (Plurinational State of)',
  'BN' => 'Brunei Darussalam',
  'SS' => 'South Sudan',
  'SY' => 'Syrian Arab Republic',
)
```

For a list of countries we *can* ship to—without those we cannot ship to—see the `CountryListService::allWeCanShipTo()` method described elsewhere in this document.


##### getCurrencies

***UNDER CONSTRUCTION, LIKELY SOME NEED UPDATING***

* `LocationReferenceService::getCurrencies($alpha2)` Pass a two-letter country code, get an array of three-letter currency codes.LocationReferenceService

`LocationReferenceService::getCurrencies('ZW')` returns:

```
[
  0 => 'BWP',
  1 => 'EUR',
  2 => 'GBP',
  3 => 'USD',
  4 => 'ZAR',
]
```

If you need more information about the currencies, set optional param 2 to true. `LocationReferenceService::getCurrencies('ZW, true')` returns:

```
[
  'BWP' => [
    'name' => 'Pula',
    'local-name' => 'Pula',
    'numeric-code' => '072',
  ],
  'EUR' => [
    'name' => 'Euro',
    'local-name' => 'Euro',
    'numeric-code' => '978',
  ],
  'GBP' => [
    'name' => 'Pound Sterling',
    'local-name' => 'Pound Sterling',
    'numeric-code' => '826',
  ],
  'USD' => [
    'name' => 'US Dollar',
    'local-name' => 'US Dollar',
    'numeric-code' => '840',
  ],
  'ZAR' => [
    'name' => 'Rand',
    'local-name' => 'Rand',
    'numeric-code' => '710',
  ],
]
```

##### alpha2 and name

With the variables

```
$country = 'Zimbabwe';
$alpha2 = 'ZW';
```

`LocationReferenceService::name($alpha2)` returns `"Zimbabwe"`.

`LocationReferenceService::alpha2($country)` returns `"ZW"`.
```
