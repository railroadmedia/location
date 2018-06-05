<?php

namespace Railroad\Location\Services;


use Illuminate\Session\Store;


class LocationService
{
    private $session;

    CONST COUNTRY_SESSION_KEY = 'ip-location-country';
    CONST REGION_SESSION_KEY = 'ip-location-region';
    CONST LATITUDE_SESSION_KEY = 'ip-location-latitude';
    CONST LONGITUDE_SESSION_KEY = 'ip-location-longitude';
    CONST CITY_SESSION_KEY = 'ip-location-city';
    CONST COUNTRY_CODE_SESSION_KEY = 'ip-location-country-code';

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /** If the country not exist on the session call the method that store data on session and return the country
     * @return mixed
     */
    public function getCountry()
    {
        if (!$this->session->has($this->getClientIp() . self::COUNTRY_SESSION_KEY)) {
            $this->requestAndStoreLocation();
        }

        return $this->session->get($this->getClientIp() . self::COUNTRY_SESSION_KEY);
    }

    /** If the region name not exist on the session call the method that store data on session and return the region name
     * @return mixed
     */
    public function getRegion()
    {
        if (!$this->session->has($this->getClientIp() . self::REGION_SESSION_KEY)) {
            $this->requestAndStoreLocation();
        }

        return $this->session->get($this->getClientIp() . self::REGION_SESSION_KEY);
    }

    /** If the latitude not exist on the session call the method that store data on session and return the latitude
     * @return mixed
     */
    public function getLatitude()
    {
        if (!$this->session->has($this->getClientIp() . self::LATITUDE_SESSION_KEY)) {
            $this->requestAndStoreLocation();
        }

        return $this->session->get($this->getClientIp() . self::LATITUDE_SESSION_KEY);
    }

    /** If the longitude not exist on the session call the method that store data on session and return the longitude
     * @return mixed
     */
    public function getLongitude()
    {
        if (!$this->session->has($this->getClientIp() . self::LONGITUDE_SESSION_KEY)) {
            $this->requestAndStoreLocation();
        }

        return $this->session->get($this->getClientIp() . self::LONGITUDE_SESSION_KEY);
    }

    /** If the city not exist on the session call the method that store data on session and return the city
     * @return mixed
     */
    public function getCity()
    {
        if (!$this->session->has($this->getClientIp() . self::CITY_SESSION_KEY)) {
            $this->requestAndStoreLocation();
        }

        return $this->session->get($this->getClientIp() . self::CITY_SESSION_KEY);
    }

    public function getCurrency()
    {
        if (!$this->session->has($this->getClientIp() . self::COUNTRY_CODE_SESSION_KEY)) {
            $this->requestAndStoreLocation();
        }

        $currencies = json_decode(file_get_contents(__DIR__ . '/../../data/currencies.json'), true);

        return $currencies[strtoupper($this->session->get($this->getClientIp() . self::COUNTRY_CODE_SESSION_KEY))];
    }

    /**
     * Store on the session the country, region, latitude, longitude and city for the IP address
     */
    public function requestAndStoreLocation()
    {
        $ip = $this->getClientIp();
        $apiUrl = ConfigService::$apiDetails[ConfigService::$activeAPI]['url'] . $ip;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $data = (array)json_decode(curl_exec($ch));
        curl_close($ch);

        if (array_key_exists(ConfigService::$apiDetails[ConfigService::$activeAPI]['latitudeKey'], $data)) {
            $this->session->put($ip . self::COUNTRY_SESSION_KEY, $data[ConfigService::$apiDetails[ConfigService::$activeAPI]['countryKey']]);
            $this->session->put($ip . self::REGION_SESSION_KEY, $data[ConfigService::$apiDetails[ConfigService::$activeAPI]['regionNameKey']]);
            $this->session->put($ip . self::LATITUDE_SESSION_KEY, $data[ConfigService::$apiDetails[ConfigService::$activeAPI]['latitudeKey']]);
            $this->session->put($ip . self::LONGITUDE_SESSION_KEY, $data[ConfigService::$apiDetails[ConfigService::$activeAPI]['longitudeKey']]);
            $this->session->put($ip . self::COUNTRY_CODE_SESSION_KEY, $data[ConfigService::$apiDetails[ConfigService::$activeAPI]['countryCodeKey']]);
            if (array_key_exists('cityKey', ConfigService::$apiDetails[ConfigService::$activeAPI])) {
                $this->session->put($ip . self::CITY_SESSION_KEY, $data[ConfigService::$apiDetails[ConfigService::$activeAPI]['cityKey']]);
            }
        }
    }

    /** Get client IP address
     * @return string
     */
    public function getClientIp()
    {
        if (ConfigService::$environment == 'development') {
            return ConfigService::$testingIP;
        }
        if (!empty(request()->server('HTTP_CLIENT_IP'))) {
            $ip = request()->server('HTTP_CLIENT_IP');
        } elseif (!empty(request()->server('HTTP_X_FORWARDED_FOR'))) {
            $ip = request()->server('HTTP_X_FORWARDED_FOR');
        } else {
            $ip = request()->server('REMOTE_ADDR');
        }
        return explode(',', $ip)[0] ?? '';
    }

    /**
     * @return array
     */
    public static function countries()
    {
        return config('location.countries', []);
    }

    /**
     * @param $country
     * @return array
     */
    public static function countryRegions($country)
    {
        return config('location.country_regions')[$country] ?? [];
    }
    /**
     * @param $country
     * @return array
     */
    public static function countryCode($countryName)
    {
        return array_reverse(config('location.countries'))[$countryName] ?? '';
    }
}