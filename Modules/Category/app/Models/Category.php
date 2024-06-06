<?php

namespace Modules\Category\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\Database\Factories\CategoryFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, Searchable, SoftDeletes, InteractsWithMedia;

    protected $withCount = ['media'];
    
    protected $fillable = [
        'name',
        'remarks',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
        'category_id'       => 'integer',
    ];

    public static function searchable()
    {
        return ['name'];
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub()
    {
        return $this->hasOne(Category::class);
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
        return CategoryFactory::new();
    }
}
