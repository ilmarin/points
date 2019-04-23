<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CityTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(array $names) {
        $prepared = array_map(function($name) {
            return [
                'name' => $name,
            ];
        }, $names);
        
        DB::table('city')->insert($prepared);
    }

}
