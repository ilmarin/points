<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * Point model
 *
 * @property int $id
 * @property string $name
 * @property \Grimzy\LaravelMysqlSpatial\Types\Point $location
 * @property string $desc
 * @property int $city_id
 */
class Point extends Model
{

    use SpatialTrait;

    public $timestamps = false;
    protected $table = 'point';
    protected $hidden = ['id', 'location', 'city_id', 'city'];
    protected $spatialFields = [
        'location'
    ];

    public function city()
    {
        return $this->hasOne('App\City', 'id', 'city_id');
    }

}
