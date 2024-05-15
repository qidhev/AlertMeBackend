<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeNotification extends Model
{
    use HasFactory;

    protected $table = "notifications_type";

    protected $fillable = [
        'id',
        'name',
        'topic'
    ];
}
