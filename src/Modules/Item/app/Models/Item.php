<?php

namespace Modules\Item\Models;

use App\Enums\ActiveEnum;
use App\Enums\BarcodeTypesEnum;
use App\Enums\TaxTypesEnum;
use App\Enums\ItemTypesEnum;
use App\Enums\ProductTypesEnum;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Brand\Models\Brand;
use Modules\Category\Models\Category;
use Modules\Detail\Models\Detail;
use Modules\Item\Database\Factories\ItemFactory;
use Modules\Patch\Models\Patch;
use Modules\Purchase\Models\Purchase;
use Modules\Stock\Models\Stock;
use Modules\Unit\Models\Unit;
use Modules\Variant\Models\Variant;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Item extends Model implements HasMedia
{
    use HasFactory, Searchable, SoftDeletes, InteractsWithMedia;

    protected $withCount = ['media'];

    protected $fillable = [
        'name',
        'label',
        'item_desc',
        'cost',
        'price',
        'active_image',
        'sku',
        'code',
        'barcode_type',
        'tax',
        'tax_type',
        'stock_alert',
        'category_id',
        'brand_id',
        'unit_id',
        'sale_unit_id',
        'purchase_unit_id',
        'remarks',
        'type',
        'product_type',
        'is_active',
        'is_available_for_purchase',
        'is_available_for_edit_in_sale',
        'is_available_for_edit_in_purchase',
        'is_available_for_sale',
    ];

    protected $casts = [
        'is_active'                             => ActiveEnum::class,
        'is_available_for_purchase'             => ActiveEnum::class,
        'is_available_for_edit_in_sale'         => ActiveEnum::class,
        'is_available_for_edit_in_purchase'     => ActiveEnum::class,
        'is_available_for_sale'                 => ActiveEnum::class,
        'type'                                  => ItemTypesEnum::class,
        'product_type'                          => ProductTypesEnum::class,
        'tax_type'                              => TaxTypesEnum::class,
        'barcode_type'                          => BarcodeTypesEnum::class,

        'unit_id'           => 'integer',
        'sale_unit_id'      => 'integer',
        'purchase_unit_id'  => 'integer',
        'category_id'       => 'integer',
        'brand_id'          => 'integer'
    ];

    const DIVIDE = '/';
    
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

    public function tax(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id')->withTrashed();
    }

    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'item_id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class)->whereNull('variant_id')->orderBy('warehouse_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'item_id');
    }

    public function details()
    {
        return $this->hasMany(Detail::class, 'item_id');
    }

    public function patches()
    {
        return $this->hasMany(Patch::class);
    }

    public static function searchable()
    {
        return ['name', 'category.name', 'brand.name'];
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('item_images')
            ->width(36)
            ->height(36)
            ->nonQueued();
    }

    protected static function newFactory()
    {
        return ItemFactory::new();
    }

    public static function getTaxDetails($item, $variant = [])
    {
        $itemWith = $item->load('purchaseUnit', 'saleUnit');

        if ($item->tax_type === TaxTypesEnum::INCLUSIVE->value) {
            // { id: 2, name: "Inclusive" }
            return self::Inclusive($itemWith, $variant);
        }
        // { id: 1, name: "Exclusive" },
        return self::Exclusive($itemWith, $variant);
    }

    protected static function Exclusive($item, $variant)
    {
        $itemCost = $item->type === ItemTypesEnum::VARIABLE ? $variant?->cost : $item?->cost;

        $tax_cost = $itemCost * ($item->tax / 100);
        $net_cost = 0;
        $total_cost = $tax_cost + $itemCost;

        $purchaseOperator = $item->type === ItemTypesEnum::SERVICE ? '*' : $item->purchaseUnit->operator;
        $purchaseOperatorValue = $item->type === ItemTypesEnum::SERVICE ? 1 : $item->purchaseUnit->operator_value;

        $net_cost = self::calculate($itemCost, $purchaseOperator, $purchaseOperatorValue);
        $tax_cost = self::calculate($tax_cost, $purchaseOperator, $purchaseOperatorValue);
        $total_cost = self::calculate($total_cost, $purchaseOperator, $purchaseOperatorValue);

        $itemPrice = $item->type === ItemTypesEnum::VARIABLE ? $variant?->price : $item?->price;

        $tax_price = $itemPrice * ($item->tax / 100);
        $net_price = 0;
        $total_price = $tax_price + $itemPrice;

        $saleOperator = $item->type === ItemTypesEnum::SERVICE ? '*' : $item->saleUnit->operator;
        $saleOperatorValue = $item->type === ItemTypesEnum::SERVICE ? 1 : $item->saleUnit->operator_value;

        $net_price = self::calculate($itemPrice, $saleOperator, $saleOperatorValue);
        $tax_price = self::calculate($tax_price, $purchaseOperator, $purchaseOperatorValue);
        $total_price = self::calculate($total_price, $purchaseOperator, $purchaseOperatorValue);

        return [
            'tax_cost' => +$tax_cost,
            'net_cost' => +$net_cost,
            'unit_cost' => +$net_cost,
            'total_cost' => +$total_cost,

            'tax_price' => +$tax_price,
            'net_price' => +$net_price,
            'unit_price' => +$net_price,
            'total_price' => +$total_price,
        ];
    }

    protected static function Inclusive($item, $variant)
    {
        $itemCost = $item->type === ItemTypesEnum::VARIABLE ? $variant?->cost : $item?->cost;

        $really_net_cost = $itemCost / (1 + ($item->tax / 100));
        $tax_cost = $itemCost - $really_net_cost;
        $net_cost = 0;

        $purchaseOperator = $item->purchaseUnit->operator;
        $purchaseOperatorValue = $item->purchaseUnit->operator_value;

        $net_cost = self::calculate($really_net_cost, $purchaseOperator, $purchaseOperatorValue);
        $tax_cost = self::calculate($tax_cost, $purchaseOperator, $purchaseOperatorValue);
        $total_cost = self::calculate($itemCost, $purchaseOperator, $purchaseOperatorValue);

        $itemPrice = $item->type === ItemTypesEnum::VARIABLE ? $variant?->price : $item?->price;

        $net_price = 0;
        $really_net_price = $itemPrice / (1 + ($item->tax / 100));
        $tax_price = $itemPrice - $really_net_price;

        $saleOperator = $item->saleUnit->operator;
        $saleOperatorValue = $item->saleUnit->operator_value;

        $net_price = self::calculate($really_net_price, $saleOperator, $saleOperatorValue);
        $tax_price = self::calculate($tax_price, $saleOperator, $saleOperatorValue);
        $total_price = self::calculate($itemPrice, $saleOperator, $saleOperatorValue);

        return [
            'tax_cost' => round($tax_cost, 4),
            'net_cost' => round($net_cost, 4),
            'unit_cost' => round(($net_cost + $tax_cost), 4),
            'total_cost' => round($total_cost, 4),

            'tax_price' => round($tax_price, 4),
            'net_price' => round($net_price, 4),
            'unit_price' => round(($net_price + $tax_price), 4),
            'total_price' => round($total_price, 4),
        ];
    }

    protected static function multiply($number, $operatorValue)
    {
        return $number * $operatorValue;
    }

    protected static function divide($number, $operatorValue)
    {
        return $number / $operatorValue;
    }

    protected static function calculate($number, $operator, $operatorValue)
    {
        return ($operator === self::DIVIDE) ?
            self::divide($number, $operatorValue) :
            self::multiply($number, $operatorValue);
    }
}
