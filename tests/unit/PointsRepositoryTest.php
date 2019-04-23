<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\PointsRepository;
use App\CitiesRepository;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use App\City;
use App\ServicesClient;
use Illuminate\Support\Collection;

/**
 * Тест репозитория точек интереса
 *
 * @author i.marinin
 */
class PointsRepositoryTest extends TestCase {

    use DatabaseMigrations;

    public function testCreate(): void {
        $data = [
            'lat' => self::LAT_DEFAULT,
            'lon' => self::LON_DEFAULT,
            'city' => 'Perm',
            'name' => 'office',
            'desc' => 'my office',
        ];

        $cities = Mockery::mock(CitiesRepository::class);

        $cities->shouldReceive('createFromCoords')
                ->with(self::LAT_DEFAULT, self::LON_DEFAULT)
                ->once()
                ->andReturn(City::create(['name' => 'Perm']));

        $cities->shouldReceive('findByName')
                ->with('Perm')
                ->once()
                ->andReturn(null);

        $repository = new PointsRepository($cities);

        $repository->create($data);

        $this->seeInDatabase('city', ['name' => 'Perm']);

        $this->seeInDatabase('point', [
            'name' => 'office',
            'desc' => 'my office',
            'location' => DB::raw("ST_GeomFromText('" . (new Point(self::LAT_DEFAULT, self::LON_DEFAULT))->toWKT() . "')"),
            'city_id' => 1,
        ]);
    }

    public function testUpdate(): void {
        $this->seedCities(['Perm']);

        $this->seedPoints([
            [
                'name' => 'office',
                'lat' => self::LAT_DEFAULT,
                'lon' => self::LON_DEFAULT,
                'desc' => 'my office',
                'city_id' => 1,
            ],
        ]);

        $cities = Mockery::mock(CitiesRepository::class);

        $latitude = 55.755814;
        $longitude = 37.617635;

        $cities->shouldReceive('createFromCoords')
                ->with($latitude, $longitude)
                ->once()
                ->andReturn(City::create(['name' => 'Moscow']));

        $cities->shouldReceive('findByName')
                ->with('Moscow')
                ->once()
                ->andReturn(null);

        $repository = new PointsRepository($cities);

        $repository->update(1, [
            'lat' => $latitude,
            'lon' => $longitude,
            'city' => 'Moscow',
            'name' => 'School',
            'desc' => 'School 1465',
        ]);

        $this->seeInDatabase('city', ['id' => 2, 'name' => 'Moscow']);

        $this->seeInDatabase('point', [
            'id' => 1,
            'name' => 'School',
            'desc' => 'School 1465',
            'location' => DB::raw("ST_GeomFromText('" . (new Point($latitude, $longitude))->toWKT() . "')"),
            'city_id' => 2,
        ]);
    }

    public function testFindByIpInRadius(): void {
        $this->seedData();

        $client = Mockery::mock(ServicesClient::class);

        $coordinates = new Geocoder\Model\Coordinates(self::LAT_DEFAULT, self::LON_DEFAULT);

        $client->shouldReceive('getCoordsByIp')->with('127.0.0.1')->andReturn($coordinates);

        $repository = new PointsRepository(null, $client);

        $collection = $repository->findByIpInRadius('127.0.0.1', 250);

        $this->assertSame(2, count($collection));

        $collection = $repository->findByIpInRadius('127.0.0.1', 240);

        $this->assertSame(1, count($collection));
    }

    public function testFindAllInCity(): void {
        $this->seedRandomPointsForCity(100, 'Perm');

        $repository = new PointsRepository();

        $collection = $repository->findAllInCity('Perm', 50, 0);

        $this->assertSame(50, $collection->count());

        $collection = $repository->findAllInCity('Perm', 10, 60);

        $this->assertSame(10, $collection->count());

        $collection = $repository->findAllInCity('Perm', 50, 60);

        $this->assertSame(40, $collection->count());
    }

}
