<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permissions';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['pivot'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class,'roles_permissions');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'users_permissions');
    }
}
