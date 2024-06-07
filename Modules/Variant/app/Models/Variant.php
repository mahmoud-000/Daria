<?php

namespace Modules\Variant\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Item\Models\Item;
use Modules\Stock\Models\Stock;
use Modules\Variant\Database\Factories\VariantFactory;

class Variant extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        // 'default',
        'name',
        'code',
        'cost',
        'price',
        'color',
        'is_active',
        'remarks',
        'item_id'
    ];

    protected $casts = [
        'is_active'                         => \App\Enums\ActiveEnum::class,
        'cost'              => 'double',
        'price'             => 'double',
    ];

    public static function searchable()
    {
        return ['name'];
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'variant_id')->orderBy('warehouse_id');
    }

    protected static function newFactory()
    {
        return VariantFactory::new();
    }
}
