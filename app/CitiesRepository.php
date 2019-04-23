<?php

namespace App;

/**
 * Repository for working with City model
 */
class CitiesRepository
{

    private $client;

    public function __construct(ServicesClient $client = null)
    {
        $this->client = $client ?? new ServicesClient();
    }

    public function findByName(string $name)
    {
        return City::where('name', $name)->get()->first();
    }

    public function createFromCoords(float $latitude, float $longitude): City
    {
        $name = $this->client->getCityNameByCoords($latitude, $longitude);

        return City::create([
            'name' => $name
        ]);
    }

}
