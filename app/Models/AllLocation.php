<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllLocation extends Model
{
    use HasFactory;

    // protected $table = "all_locations";

    protected $fillable = [

        'longitude', 'latitude', 'zip_code', 'address', 'time_from', 'time_to',
        'status', 'price',
    ];
}


