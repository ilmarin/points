<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * City model
 *
 * @property int $id
 * @property string $name
 */
class City extends Model
{

    public $timestamps = false;
    protected $table = 'city';
    protected $fillable = [
        'name'
    ];

}
