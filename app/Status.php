<?php

namespace App;

use DB;
use Geocoder;
use Http\Client\Curl\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Returns health check status
 *
 * @author i.marinin
 */
class Status
{

    /**
     *
     * @var Client
     */
    protected $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client();
    }

    /**
     * Returns statuses array
     *
     * @return array
     */
    public function get(): array
    {
        $db = $this->getDbStatus();
        $redis = $this->getRedisStatus();
        $yandex = $this->getYandexStatus();
        $ipstack = $this->getIpStackStatus();

        $services = compact('db', 'redis', 'yandex', 'ipstack');

        $healthStatus = ($db && $redis && $yandex && $ipstack) ? 'OK' : 'PROBLEMS';

        $result = [
            'health' => $healthStatus,
            'services' => $services
        ];

        return $result;
    }

    /**
     * DB connection status
     *
     * @return bool
     */
    private function getDbStatus(): bool
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Redis connection status
     *
     * @return bool
     */
    private function getRedisStatus(): bool
    {
        try {
            app('redis')->connection('geocode-cache');
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Yandex GEO service availability status
     *
     * @return bool
     */
    private function getYandexStatus(): bool
    {
        $request = new Request('GET', 'https://geocode-maps.yandex.ru/1.x/?format=json');
        $response = $this->client->sendRequest($request);

        return $response->getStatusCode() === 200;
    }

    /**
     * IpStack.com service availability status
     *
     * @return bool
     */
    private function getIpStackStatus(): bool
    {
        $request = new Request('GET', 'http://api.ipstack.com/127.0.0.1?access_key=' . env('IP_STACK_KEY'));
        $response = $this->client->sendRequest($request);

        return $response->getStatusCode() === 200;
    }

}
