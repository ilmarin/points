<?php

use App\CitiesRepository;
use Laravel\Lumen\Testing\DatabaseMigrations;
use App\ServicesClient;

class CitiesRepositoryTest extends TestCase {

    use DatabaseMigrations;

    public function testCreateFromCoords() {
        $client = Mockery::mock(ServicesClient::class);

        $client->shouldReceive('getCityNameByCoords')->once()->with(self::LAT_DEFAULT, self::LON_DEFAULT)->andReturn('Perm');

        $repository = new CitiesRepository($client);
        $repository->createFromCoords(self::LAT_DEFAULT, self::LON_DEFAULT);

        $this->seeInDatabase('city', ['name' => 'Perm', 'id' => 1]);
    }

    public function findByName() {
        $this->seedCities([
            'Perm'
        ]);

        $repository = new CitiesRepository();
        $city = $repository->findByName('Perm');

        $this->assertSame(1, $city->id);
    }

}
