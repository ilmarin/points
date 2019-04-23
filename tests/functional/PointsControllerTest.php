<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Grimzy\LaravelMysqlSpatial\Types\Point;

/**
 * API test
 *
 * @author ilya
 */
class PointsControllerTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Point creation test
     */
    public function testCreate(): void
    {
        $data = [
            'lat' => self::LAT_DEFAULT,
            'lon' => self::LON_DEFAULT,
            'city' => 'Perm',
            'name' => 'office',
            'desc' => 'my office',
            'api_token' => env('API_TOKEN')
        ];

        $response = $this->json('POST', '/api/v1/points', $data);

        $response->seeJsonEquals([
            'success' => true
        ]);

        $this->seeInDatabase('point', [
            'name' => 'office',
            'desc' => 'my office',
            'location' => DB::raw("ST_GeomFromText('" . (new Point(self::LAT_DEFAULT, self::LON_DEFAULT))->toWKT() . "')"),
            'city_id' => 1
        ]);
    }

    /**
     * Validation errors test
     */
    public function testCreateAndUpdateValidationErrors(): void
    {
        $data = [
            'lat' => 123,
            'lon' => 456,
            'city' => 'Perm',
            'name' => '',
            'desc' => '',
            'api_token' => env('API_TOKEN')
        ];

        $response = $this->json('POST', '/api/v1/points', $data);

        $response->seeJson([
            'success' => false,
            'errors' => [
                'validation' => [
                    'lat' => [
                        'The lat must be valid latitude.'
                    ],
                    'lon' => [
                        'The lon must be valid longitude.'
                    ],
                    'desc' => [
                        'The desc field is required.'
                    ],
                    'name' => [
                        'The name field is required.'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Point update test
     */
    public function testUpdate(): void
    {
        $this->seedCities([
            'Perm'
        ]);

        $this->seedPoints([
            [
                'name' => 'office',
                'lat' => self::LAT_DEFAULT,
                'lon' => self::LON_DEFAULT,
                'desc' => 'my office',
                'city_id' => 1
            ]
        ]);

        $latitude = 55.755814;
        $longitude = 37.617635;

        $data = [
            'lat' => $latitude,
            'lon' => $longitude,
            'city' => 'Moscow',
            'name' => 'School',
            'desc' => 'School 1465',
            'api_token' => env('API_TOKEN')
        ];

        $response = $this->json('PUT', '/api/v1/points/1', $data);

        $response->seeJsonEquals([
            'success' => true
        ]);

        $this->seeInDatabase('city', [
            'id' => 2,
            'name' => 'Moscow'
        ]);

        $this->seeInDatabase('point', [
            'id' => 1,
            'name' => 'School',
            'desc' => 'School 1465',
            'location' => DB::raw("ST_GeomFromText('" . (new Point($latitude, $longitude))->toWKT() . "')"),
            'city_id' => 2
        ]);
    }

    /**
     * Finding nearest points by IP in radius
     */
    public function testFindByIpInRadius(): void
    {
        $this->seedData();

        $response = $this->json('GET', '/api/v1/points/inrad?rad=10000&ip=' . env('TEST_IP') . '&api_token=' . env('API_TOKEN'));

        $response->seeJson([
            'success' => true,
            'data' => [
                [
                    'name' => 'office',
                    'desc' => 'my office',
                    'lat' => 58.001833,
                    'lon' => 56.295739,
                    'in' => 'Perm'
                ],
                [
                    'name' => 'Shop',
                    'desc' => 'Shop near office',
                    'lat' => 58.001473,
                    'lon' => 56.299916,
                    'in' => 'Perm'
                ]
            ]
        ]);
    }

    /**
     * Finding all points in city
     */
    public function testFindAllInCity(): void
    {
        $this->seedData();

        $response = $this->json('GET', '/api/v1/points/in/Perm?api_token=' . env('API_TOKEN'));

        $response->seeJson([
            'success' => true,
            'data' => [
                [
                    'name' => 'office',
                    'desc' => 'my office',
                    'lat' => 58.001833,
                    'lon' => 56.295739,
                ],
                [
                    'name' => 'Shop',
                    'desc' => 'Shop near office',
                    'lat' => 58.001473,
                    'lon' => 56.299916,
                ]
            ]
        ]);
    }

}
