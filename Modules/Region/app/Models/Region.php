<?php

namespace Modules\Region\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Region\Database\Factories\RegionFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Region extends Model implements HasMedia
{
    use HasFactory, Searchable, SoftDeletes, InteractsWithMedia;

    protected $withCount = ['media'];
    
    protected $fillable = [
        'name',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
    ];

    public static function searchable()
    {
        return ['name'];
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('logo')
            ->width(36)
            ->height(36)
            ->nonQueued();
    }

    protected static function newFactory()
    {
        return RegionFactory::new();
    }
}
