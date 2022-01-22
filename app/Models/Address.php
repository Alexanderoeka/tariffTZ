<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'address',
        'house_fias_id',
        'region_with_type',
        'city_with_type',
        'street_with_type'
    ];





    public $timestamps = true;
}
