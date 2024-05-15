<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'message',
        'subtitle',
        'title',
        'main_text',
        'send',
        'date_start_at',
        'date_end_at',
        'parent_id',
        'type_id',
        'city_id',
        'sent_at',
        'type_location_id'
    ];

    public function type(): HasOne
    {
        return $this->hasOne(TypeNotification::class, 'id', 'type_id');
    }

    public function city(): ?HasOne
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function history(): Collection
    {
        $historyParent = self::where('parent_id', $this->parent_id)->oldest()->get();
        $historyParent->put(self::find($this->parent_id));

        return $historyParent;
    }

    public function getTopic(): string
    {
        // Получаем топик из типа
        // Если есть указание населенного пункта, то добавляем к топику название пункта на латинице
        return ($this->type->topic . ($this->city ? "/".Str::lower(Str::slug($this->city->name)) : ''));
    }
}
