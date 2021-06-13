<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excludedarea extends Model
{
    use HasFactory;

    // protected $table = "excludedareas";

    protected $fillable = [

        'zip_code',
    ];
}
