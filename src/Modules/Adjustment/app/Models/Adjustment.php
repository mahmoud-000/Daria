<?php

namespace Modules\Adjustment\Models;

use App\Traits\BaseInvoiceRelationsTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Adjustment\Database\Factories\AdjustmentFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Adjustment extends Model implements HasMedia
{
    use HasFactory,
        Searchable,
        BaseInvoiceRelationsTrait,
        SoftDeletes,
        InteractsWithMedia;

    protected $withCount = ['media'];

    protected $fillable = [
        'user_id',
        'warehouse_id',
        'pipeline_id',
        'stage_id',
        'effected',
        'remarks',
        'date',
        'items',
        'grand_total'
    ];

    protected $casts = [
        'effected' => 'boolean',
        'items' => 'integer'
    ];

    public function grandTotal(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public static function searchable()
    {
        return ['date'];
    }

    protected static function newFactory()
    {
        return AdjustmentFactory::new();
    }
}
