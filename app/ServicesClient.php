<?php

namespace App;

use Geocoder\Query\ReverseQuery;
use Geocoder\Model\Coordinates;
use Geocoder\Model\Address;
use Geocoder\Laravel\Facades\Geocoder;

/**
 * Encapsulates external services calls
 *
 * @package App
 */
class ServicesClient
{

    /**
     * Returns coords by IP address
     *
     * @param string $ip
     * @return Coordinates|null
     */
    public function getCoordsByIp(string $ip)
    {
        /* @var $data Address */

        $data = Geocoder::using('ipstack')->geocode($ip)->get()->first();

        return $data->getCoordinates();
    }

    /**
     * Returns city name by coords
     *
     * @param float $latitude
     * @param float $longitude
     * @return string
     */
    public function getCityNameByCoords(float $latitude, float $longitude): string
    {
        $query = ReverseQuery::fromCoordinates($latitude, $longitude)->withLocale(env('YANDEX_LOCALITY'));

        /* @var $data Address */

        $data = Geocoder::using('yandex')->reverseQuery($query)->get()->first();

        return $data->getLocality();
    }

}
