<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafeLocation extends Model
{
    use HasFactory;

    // protected $table = "safe_locations";

    protected $fillable = [

        'longitude', 'latitude', 'address', 'user_id'
    ];
}


