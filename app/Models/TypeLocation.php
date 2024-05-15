<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeLocation extends Model
{
    use HasFactory;

    protected $table = "locations_type";

    protected $fillable = [
        'id',
        'name'
    ];
}
