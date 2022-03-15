<?php

namespace Railroad\Location\Console\Commands;

use Illuminate\Console\Command;
use Railroad\Location\Services\LocationReferenceService;

class LocationHelper extends Command
{
    protected $signature = 'locationHelper';

    protected $description = 'Take a list of countries copied from source websites and determine alpha2 codes to use ' .
    ' in config.';

    /*
     * copy country names from sources and paste them here so that this script can attempt to create a list that you
     * can easily copy to the updated config to update it.
     */
    private static $countriesNamesToFindAlpha2CodesFor = [

        # https://about.usps.com/newsroom/service-alerts/international/welcome.htm
        'Afghanistan',
        //'Australia',      // appears to be minor notice and thus not relevant
        //'Bhutan',         // appears to be minor notice and thus not relevant
        //'Chad',           // appears to be minor notice and thus not relevant
        'Cuba',
        'Ecuador',
        //'France',         // appears to be minor notice and thus not relevant
        //'Germany',        // appears to be minor notice and thus not relevant
        //'Hong Kong',      // appears to be minor notice and thus not relevant
        'Laos',
        //'Latvia',         // appears to be minor notice and thus not relevant
        'Libya',
        //'Malaysia',       // appears to be minor notice and thus not relevant
        //'Malta',          // appears to be minor notice and thus not relevant
        //'Moldova',        // appears to be minor notice and thus not relevant
        //'Mongolia',       // appears to be minor notice and thus not relevant
        //'Netherlands',    // appears to be minor notice and thus not relevant
        //'New Zealand',    // appears to be minor notice and thus not relevant
        'Papua New Guinea',
        'Russia',
        //'Samoa',          // appears to be minor notice and thus not relevant
        'Solomon Islands',
        'South Sudan',
        'Syria',
        'Timor-Leste',
        'Turkmenistan',
        'Ukraine',
        //'Vietnam',        // appears to be minor notice and thus not relevant
        'Yemen',

        # https://about.usps.com/publications/pub699/pub699_online_017.htm
        'Cuba',
        'Iran',
        'North Korea',
        'Sudan',
        'Syria',

        # https://epostglobalshipping.com/service-updates/
        'Afghanistan',
        'Bhutan',
        'Brunei',
        'Cayman Islands',
        'Cuba',
        'East Timor (Timor-Leste)',
        'Ecuador',
        'Guadeloupe',
        'Guinea',
        'Honduras',
        'Kazakhstan',
        'Laos',
        'Libya',
        'Martinique',
        'Nauru',
        'Nicaragua',
        'Russia',
        'Solomon Islands',
        'Syria (SAR)',
        'Tajikistan',
        'Tajikistan',
        'Turkmenistan',
        'Ukraine',
    ];

    /*
     * Every time there's a country name in a source that doesn't match iso3166 add the alpha2 code and the incorrect
     * name here so that everytime you run this script it'll find the correct alpha2 dispite the incorrect name.
     */
    private static $potentialAnswers = [
        [
            'alpha2' => 'BN',
            'incorrect-name' => 'Brunei', # Brunei Darussalam
        ],
        [
            'alpha2' => 'SS',
            'incorrect-name' => 'Sudan(South)', # South Sudan
        ],
        [
            'alpha2' => 'SY',
            'incorrect-name' => 'Syria (SAR)', # Syrian Arab Republic (the)
        ],
        [
            'alpha2' => 'VE',
            'incorrect-name' => 'Venezuela', # Venezuela (Bolivarian Republic of)
        ],
        [
            'alpha2' => 'LA',
            'incorrect-name' => 'Laos', # Lao People's Democratic Republic
        ],
        [
            'alpha2' => 'RE',
            'incorrect-name' => 'Reunion (Bourbon)', # Réunion
        ],
        [
            'alpha2' => 'PM',
            'incorrect-name' => 'Saint Pierre and Miquelon (Miquelon)', # Saint Pierre and Miquelon
        ],
        [
            'alpha2' => 'SY',
            'incorrect-name' => 'Syria', # Syrian Arab Republic
        ],
        [
            'alpha2' => 'IR',
            'incorrect-name' => 'Iran', # Iran (Islamic Republic of)
        ],
        [
            'alpha2' => 'KP',
            'incorrect-name' => 'North Korea', # Korea (the Democratic People's Republic of)
        ],
        [
            'alpha2' => 'SD',
            'incorrect-name' => 'Sudan', # Sudan (the)
        ],
        [
            'alpha2' => 'MD',
            'incorrect-name' => 'Moldova' # Moldova (the Republic of)
        ],
        [
            'alpha2' => 'TL',
            'incorrect-name' => 'East Timor (Timor-Leste)' # Timor-Leste
        ],
        [
            'alpha2' => 'VN',
            'incorrect-name' => 'Vietnam' # Viet Nam
        ],
        [
            'alpha2' => 'RU',
            'incorrect-name' => 'Russia' # Russian Federation (the)
        ],
    ];

    public function handle()
    {
        $listNotFound = [];
        $listFound = [];

        foreach (self::$countriesNamesToFindAlpha2CodesFor as $name) {
            $alpha2 = LocationReferenceService::alpha2($name);
            if (empty($alpha2)) {
                $match = false;

                foreach (self::$potentialAnswers as $candidate) {
                    $alpha2Candidate = $candidate['alpha2'];
                    $candidateIncorrectName = $candidate['incorrect-name'];

                    if ($name == $candidateIncorrectName) {
                        $match = $alpha2Candidate;
                        break;
                    }
                }

                if ($match) {
                    $correctName = LocationReferenceService::name($alpha2Candidate);
                    if (empty($correctName)) {
                        dd('error 578438578345728975 for $match ' . $match . ' and $correctName ' . $correctName);
                    }
                    $listFound[$alpha2Candidate] = $correctName;
                } else {
                    $listNotFound[] = $name;
                }
            } else {
                $listFound[$alpha2] = $name;
            }
        }

        sort($listNotFound); # sort by value, asc, do NOT keep key association
        ksort($listFound); # sort by key, asc, DO keep key association

        if (!empty($listNotFound)) {
            $this->info('');
            $this->info('--------- unable to find alpha2 codes for these country names ---------');
            $this->info('');

            foreach ($listNotFound as $name) {
                $this->info($name);
            }

            $this->info('');
            $this->info(
                '(for above countries find their respective ISO 3166 alpha2 codes, add them to the ' .
                '$potentialAnswers array and then re-run the command)'
            );
            $this->info('');

            die();
        }

        $listFound = array_unique($listFound);

        $this->info('');
        $this->info('--------- copy and paste this to railroad/location/config/location.php ---------');
        $this->info('');

        foreach ($listFound as $alpha2 => $name) {
            $this->info('\'' . $alpha2 . '\', # ' . $name);
        }

        $this->info('');
        $this->info('--------- --------------------------------------------------- ---------');
        $this->info('');
    }

}