<?php

namespace Modules\Stock\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Item\Models\Item;
use Modules\Patch\Models\Patch;
use Modules\Stock\Database\Factories\StockFactory;
use Modules\Variant\Models\Variant;
use Modules\Warehouse\Models\Warehouse;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "stock";
    protected $fillable = [
        'item_id',
        'variant_id',
        'warehouse_id',
        'quantity',
        'sku',
    ];

    protected $casts = [
        'quantity'              => 'integer',
        'warehouse_id' => 'integer',
        'item_id' => 'integer',
        'variant_id' => 'integer',
    ];

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

    public function patches()
    {
        return $this->hasMany(Patch::class);
    }

    protected static function newFactory()
    {
        return StockFactory::new();
    }
}
