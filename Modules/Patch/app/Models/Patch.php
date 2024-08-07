<?php

namespace Modules\Patch\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Detail\Models\Detail;
use Modules\Item\Models\Item;
use Modules\Patch\Database\Factories\PatchFactory;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;
use Modules\Stock\Models\Stock;

class Patch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stock_id',
        'item_id',
        'variant_id',
        'warehouse_id',
        'unit_id',
        'quantity',
        'amount',
        'production_date',
        'expired_date',
    ];

    public function amount(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }
    
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function details()
    {
        return $this->hasMany(Detail::class);
    }

    protected static function newFactory()
    {
        return PatchFactory::new();
    }
}
