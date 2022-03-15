<?php

namespace Railroad\Location\Console\Commands;

use Illuminate\Console\Command;
use Railroad\Location\Services\LocationReferenceService;

class LocationHelper2 extends Command
{
    protected $signature = 'locationHelper2';

    protected $description = 'compare to lists to display added and removed countries';

    public function handle()
    {
        $before = [
            // as per the "ePost Global (PPDC, PPT)" column at https://www.epostglobalshipping.com/imp-serviceupdates.html
            'BM', # Bermuda
            'BT', # Bhutan
            'KY', # Cayman Islands
            'LY', # Libya
            'PG', # Papua New Guinea
            'TJ', # Tajikistan
            'YE', # Yemen
            'AF', # Afghanistan
            'EC', # Ecuador
            'BN', # Brunei Darussalam                   referred to as "Brunei" at epostglobalshipping.com
            'SS', # South Sudan                         referred to as "Sudan(South)" at epostglobalshipping.com
            'SY', # Syrian Arab Republic (the)          referred to as "Syria (SAR)" at epostglobalshipping.com
            'VE', # Venezuela (Bolivarian Republic of)  referred to as "Venezuela" at epostglobalshipping.com

            // as per USPS list of sanctioned countries at https://about.usps.com/publications/pub699/pub699_online_017.htm
            'CU', # Cuba
            'IR', # Iran (Islamic Republic of)
            'KP', # Korea (the Democratic People's Republic of)
            'SD', # Sudan (the)

            // as per https://about.usps.com/newsroom/service-alerts/international/welcome.htm
            'GF', # French Guiana
            'MN', # Mongolia
            'SS', # South Sudan
            'TD', # Chad
            'GP', # Guadeloupe
            'TJ', # Tajikistan
            'CU', # Cuba
            'TL', # Timor-Leste
            'TM', # Turkmenistan
            'MQ', # Martinique
            'WS', # Samoa
            'YE', # Yemen
            'LR', # Liberia
            'CF', # Central African Republic (the)
            'YT', # Mayotte
            'SL', # Sierra Leone
            'LA', # Lao People's Democratic Republic    # refered to as "Laos" at USPS source
            'RE', # Réunion                             # refered to as "Reunion (Bourbon)" at USPS source
            'PM', # Saint Pierre and Miquelon           # refered to as "Saint Pierre and Miquelon (Miquelon)" at USPS source
        ];

        $after = [
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
        ];

        $inBeforeButNotAfter = array_diff($before, $after);

        $inAfterButNotBefore = array_diff($after, $before);

        sort($inBeforeButNotAfter);
        sort($inAfterButNotBefore);

        $this->info('');
        $this->info('Removed');
        $this->info('--------------------');

        foreach ($inBeforeButNotAfter as $code) {
            $countryName = LocationReferenceService::name($code);
            $this->info($countryName . ' (' . $code . ')');
        }

        $this->info('');
        $this->info('Added');
        $this->info('--------------------');

        foreach ($inAfterButNotBefore as $code) {
            $countryName = LocationReferenceService::name($code);
            $this->info($countryName . ' (' . $code . ')');
        }

        $this->info('');
    }
}