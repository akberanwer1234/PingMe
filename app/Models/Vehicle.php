<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // protected $table = "vehicles";

    protected $fillable = [

        'nick_name', 'reg_number', 'plate_number', 'color_of_plate_id',
        'plate_source_id', 'plate_category_id', 'user_id',
    ];
}


