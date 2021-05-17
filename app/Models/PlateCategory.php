<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlateCategory extends Model
{
    use HasFactory;

    // protected $table = "plate_categories";

    protected $fillable = [

        'plate_category',
    ];
}


