<?php

namespace Modules\Quotation\Entities;

use App\Traits\InvoiceCustomerTrait;
use App\Traits\InvoiceDetailsTrait;
use App\Traits\InvoicePipelineTrait;
use App\Traits\InvoiceWarehouseTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Quotation\Database\Factories\QuotationFactory;

class Quotation extends Model
{
    use HasFactory,
        Searchable,
        SoftDeletes,
        InvoicePipelineTrait,
        InvoiceCustomerTrait,
        InvoiceDetailsTrait,
        InvoiceWarehouseTrait;

    protected $fillable = [
        'user_id',
        'customer_id',
        'warehouse_id',
        'pipeline_id',
        'stage_id',
        'reason_id',
        'effected',
        'discount',
        'remarks',
        'date',
        'ref',
        'tax',
        'tax_net',
        'paid_amount',
        'grand_total',
        'shipping_type',
        'shipper_id',
        'shipping',
        'is_active'
    ];

    protected $casts = [
        'effected' => 'boolean',
        'shipping_type' => 'integer',

        'tax' => 'float',
        'tax_net' => 'float',
        'paid_amount' => 'float',
        'grand_total' => 'float',
        'discount' => 'float',
        'shipping' => 'float',
        'is_active'         => \App\Enums\ActiveEnum::class,
    ];

    public function isActived()
    {
        return $this->is_active === \App\Enums\ActiveEnum::ACTIVED;
    }

    public static  function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
        static::created(function ($model) {
            $model->ref = 'QI_' . $model->id + 1000;
            $model->save();
        });
    }

    public static function searchable()
    {
        return ['ref'];
    }

    protected static function newFactory()
    {
        return QuotationFactory::new();
    }
}
