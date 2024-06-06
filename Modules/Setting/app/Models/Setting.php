<?php

namespace Modules\Setting\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Setting\Database\factories\SettingFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $withCount = ['media'];
    
    public $timestamps = false;
    public $with = ['media'];
    public $fillable = [
        'user_id',
        'key',
        'value',
    ];

    public function scopeByUser(Builder $query, int $user_id = null): Builder
    {
        if (is_null($user_id) && auth()->check()) {
            $user_id = auth()->id();
        }
        return $query->where('user_id', $user_id);
    }

    public function scopeSystemOnly(Builder $query): Builder
    {
        return $query->whereNull('user_id');
    }

    protected static function checkConfigMail()
    {
        $mail = config('mail');
        return isset($mail['driver']) && isset($mail['host']) && isset($mail['port']) && isset($mail['username']) && isset($mail['password']) && isset($mail['from']['address']) && isset($mail['from']['name']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('system_logo')
            ->width(100)
            ->height(100)
            ->nonQueued();
    }

    protected static function newFactory()
    {
        return SettingFactory::new();
    }
}
