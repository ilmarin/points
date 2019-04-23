<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class PointTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(array $points) {
        $prepared = array_map(function($data) {
            return [
                'name' => $data['name'],
                'location' => DB::raw("ST_GeomFromText('" . (new Point($data['lat'], $data['lon']))->toWKT() . "')"),
                'desc' => $data['desc'],
                'city_id' => $data['city_id'],
            ];
        }, $points);

        DB::table('point')->insert($prepared);
    }

}
