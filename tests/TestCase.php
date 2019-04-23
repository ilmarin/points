<?php

use Faker\Factory as Faker;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase {

    const LAT_DEFAULT = 58.001833;
    const LON_DEFAULT = 56.295739;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication() {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    protected function seedCities(array $names): void {
        $citiesSeeder = new CityTableSeeder();

        $citiesSeeder->run($names);
    }

    protected function seedPoints(array $names): void {
        $citiesSeeder = new PointTableSeeder();

        $citiesSeeder->run($names);
    }

    protected function seedData(): void {
        $this->seedCities([
            'Perm',
            'Moscow'
        ]);

        $this->seedPoints([
            [
                'name' => 'office',
                'lat' => self::LAT_DEFAULT,
                'lon' => self::LON_DEFAULT,
                'desc' => 'my office',
                'city_id' => 1
            ],
            [
                'name' => 'Shop',
                'desc' => 'Shop near office',
                'lat' => 58.001473,
                'lon' => 56.299916,
                'city_id' => 1
            ],
            [
                'id' => 1,
                'name' => 'School',
                'lat' => 55.755814,
                'lon' => 37.617635,
                'desc' => 'School 1465',
                'city_id' => 2
            ]
        ]);
    }

    protected function seedRandomPointsForCity(int $number, string $city) {
        $this->seedCities([
            $city
        ]);

        $rows = [];

        $faker = Faker::create();

        for ($i = 0; $i < $number; $i ++) {
            $rows[] = [
                'name' => 'Point of ' . $faker->streetName,
                'desc' => 'Simple desc',
                'lat' => $faker->latitude,
                'lon' => $faker->longitude,
                'city_id' => 1
            ];
        }

        $this->seedPoints($rows);
    }

}
