<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllLocationImage extends Model
{
    use HasFactory;

    // protected $table = "all_location_images";

    protected $fillable = [

        'avatar', 'all_location_id',
    ];
}



