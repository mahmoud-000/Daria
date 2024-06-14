<?php

namespace Modules\Company\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Company\Database\Factories\CompanyFactory;
use Modules\Branch\Models\Branch;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Company extends Model implements HasMedia
{
    use HasFactory, Searchable, SoftDeletes, InteractsWithMedia;

    protected $withCount = ['media'];
    
    protected $fillable = [
        'name',
        'currency',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
    ];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public static function searchable()
    {
        return ['name'];
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('logo')
            ->width(80)
            ->height(80)
            ->nonQueued();
    }

    protected static function newFactory()
    {
        return CompanyFactory::new();
    }
}
