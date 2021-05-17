<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorOfPlate extends Model
{
    use HasFactory;

    // protected $table = "color_of_plates";

    protected $fillable = [

        'color',
    ];
}


