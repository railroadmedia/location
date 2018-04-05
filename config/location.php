<?php

return [
    'environment' => 'development',
    'testing_ip' => '70.69.219.138',
    'active_api' => 'ip-api.com',
    'api' =>
        [
            'freegeoip.net' => [
                'url' => 'http://freegeoip.net/json/',
                'countryKey' => 'country_name',
                'countryCodeKey' => 'country_code',
                'regionNameKey' => 'region_name',
                'latitudeKey' => 'latitude',
                'longitudeKey' => 'longitude'
            ],
            'ip-api.com' =>[
                'url' => 'http://ip-api.com/json/',
                'countryKey' => 'country',
                'countryCodeKey' => 'countryCode',
                'regionNameKey' => 'regionName',
                'latitudeKey' => 'lat',
                'longitudeKey' => 'lon',
                'cityKey' => 'city'
            ]
        ],
];