<?php

namespace Modules\Variant\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Item\Models\Item;
use Modules\Stock\Models\Stock;
use Modules\Variant\Database\Factories\VariantFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Variant extends Model implements HasMedia
{
    use HasFactory, Searchable, SoftDeletes, InteractsWithMedia;

    protected $withCount = ['media'];
    
    protected $fillable = [
        'name',
        'code',
        'sku',
        'cost',
        'price',
        'is_active',
        'remarks',
        'item_id'
    ];

    protected $casts = [
        'is_active'                         => \App\Enums\ActiveEnum::class,
    ];

    public function cost(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public function price(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public static function searchable()
    {
        return ['name', 'sku', 'code'];
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'variant_id')->orderBy('warehouse_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('image')
            ->width(36)
            ->height(36)
            ->nonQueued();
    }

    protected static function newFactory()
    {
        return VariantFactory::new();
    }
}
