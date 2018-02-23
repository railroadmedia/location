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
                'regionNameKey' => 'region_name',
                'latitudeKey' => 'latitude',
                'longitudeKey' => 'longitude'
            ],
            'ip-api.com' =>[
                'url' => 'http://ip-api.com/json/',
                'countryKey' => 'country',
                'regionNameKey' => 'regionName',
                'latitudeKey' => 'lat',
                'longitudeKey' => 'lon',
                'cityKey' => 'city'
            ]
        ],
];