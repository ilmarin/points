<?php

namespace App;

use DB;
use App\ServicesClient;
use Exception;
use Grimzy\LaravelMysqlSpatial\Types\Point as PointData;
use Illuminate\Support\Collection;

class PointsRepository
{

    /**
     *
     * @var CitiesRepository
     */
    protected $cities;

    /**
     *
     * @var ServicesClient
     */
    protected $client;

    public function __construct(CitiesRepository $cities = null, ServicesClient $client = null)
    {
        $this->cities = $cities ?? new CitiesRepository();
        $this->client = $client ?? new ServicesClient();
    }

    /**
     * Saves point to database
     *
     * @param array $data
     * @return bool
     * @throws PointsRepositoryException
     */
    public function create(array $data): bool
    {
        $point = new Point();

        try {
            $result = $this->fill($point, $data);
        } catch (Exception $ex) {
            throw new PointsRepositoryException('Point creation error', $data, $ex);
        }

        return $result ?? false;
    }

    /**
     * Fills point data
     *
     * @param \App\Point $point
     * @param array $data
     * @return bool
     * @throws \PDOException
     */
    private function fill(Point $point, array $data): bool
    {
        $result = false;

        try {
            DB::beginTransaction();

            $latitude = $data['lat'] ?? $point->location->getLat();
            $longitude = $data['lon'] ?? $point->location->getLng();

            $point->location = new PointData($latitude, $longitude);

            if (isset($data['city'])) {
                $cityId = $this->getCityId($data['city'], $latitude, $longitude);
                $point->city_id = $cityId;
            }

            if (isset($data['name'])) {
                $point->name = $data['name'];
            }

            if (isset($data['desc'])) {
                $point->desc = $data['desc'];
            }

            $result = $point->save();

            $result ? DB::commit() : DB::rollBack();

            return $result;
        } catch (\PDOException $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * Updates point
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws PointsRepositoryException
     */
    public function update(int $id, array $data): bool
    {
        try {
            $point = Point::findOrFail($id);
            return $this->fill($point, $data);
        } catch (Exception $ex) {
            throw new PointsRepositoryException('Point update error', ['id' => $id, 'data' => $data], $ex);
        }
    }

    /**
     * Getting neareast points by ip and radius
     *
     * @param string $ip
     * @param int $radius
     * @return Collection
     * @throws PointsRepositoryException
     */
    public function findByIpInRadius(string $ip, int $radius): Collection
    {
        try {
            $coords = $this->client->getCoordsByIp($ip);

            $point = new PointData($coords->getLatitude(), $coords->getLongitude());

            $collection = Point::distanceSphere('location', $point, $radius)->get();

            return $collection;
        } catch (Exception $ex) {
            throw new PointsRepositoryException('Points search error', [
                'ip' => $ip,
                'radius' => $radius
            ], $ex);
        }
    }

    /**
     * Finds all points in city
     *
     * @param string $name
     * @param int $limit
     * @param int $offset
     * @return Collection
     * @throws PointsRepositoryException
     */
    public function findAllInCity(string $name, int $limit, int $offset): Collection
    {
        try {
            $city = $this->cities->findByName($name);

            $collection = Point::where([
                'city_id' => $city->id
            ])->offset($offset)
                ->limit($limit)
                ->get();

            return $collection;
        } catch (Exception $ex) {
            throw new PointsRepositoryException('Points search error', [
                'name' => $name,
                'limit' => $limit,
                'offset' => $offset,
            ], $ex);
        }
    }

    /**
     * Get city name
     *
     * If city is not presented in storage trying get it from external service
     *
     * @param string $cityName
     * @param float $latitude
     * @param float $longitude
     * @return int
     * @throws PointsRepositoryException
     */
    private function getCityId(string $cityName, float $latitude, float $longitude): int
    {
        try {
            $city = $this->cities->findByName($cityName);

            if (!$city) {
                $city = $this->cities->createFromCoords($latitude, $longitude);
            }

            return $city->id;
        } catch (Exception $ex) {
            throw new PointsRepositoryException('Error getting city id', ['name' => $cityName, 'lat' => $latitude, 'lon' => $longitude], $ex);
        }
    }

}
