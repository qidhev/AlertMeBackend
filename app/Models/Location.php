<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'latitude',
        'address',
        'longitude',
        'city_id',
        'type_id'
    ];

    public function type(): HasOne
    {
        return $this->hasOne(TypeLocation::class, 'id', 'type_id');
    }
}
