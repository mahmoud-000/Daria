<?php

namespace Modules\SaleReturn\Entities;

use App\Traits\InvoiceDetailsTrait;
use App\Traits\InvoicePaymentsTrait;
use App\Traits\InvoicePipelineTrait;
use App\Traits\InvoiceQrCodeTrait;
use App\Traits\InvoiceSupplierTrait;
use App\Traits\InvoiceWarehouseTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\SaleReturn\Database\Factories\SaleReturnFactory;

class SaleReturn extends Model
{
    use HasFactory,
        Searchable,
        SoftDeletes,
        InvoicePipelineTrait,
        InvoiceSupplierTrait,
        InvoiceDetailsTrait,
        InvoicePaymentsTrait,
        InvoiceWarehouseTrait,
        InvoiceQrCodeTrait;

    protected $fillable = [
        'user_id',
        'supplier_id',
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
        'payment_status',
        'grand_total',
        'shipping_type',
        'shipper_id',
        'shipping',
        'is_active'
    ];

    protected $casts = [
        'effected' => 'boolean',
        'payment_status' => 'integer',
        'shipping_type' => 'integer',

        'tax' => 'double',
        'tax_net' => 'double',
        'paid_amount' => 'double',
        'grand_total' => 'double',
        'discount' => 'double',
        'shipping' => 'double',
        'is_active'         => \App\Enums\ActiveEnum::class,
    ];

    const PAID = 1;
    const UNPAID = 0;

    public function isActived()
    {
        return $this->is_active === \App\Enums\ActiveEnum::ACTIVED;
    }

    public static  function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = auth()->id();
            $model->payment_status = floatval($model->paid_amount) === floatval($model->grand_total) ? self::PAID : self::UNPAID;
        });
        static::created(function ($model) {
            $model->ref = 'PI_' . $model->id + 1000;
            $model->save();
        });
        static::updating(function ($model) {
            $model->payment_status =
                floatval($model->paid_amount) === floatval($model->grand_total) ? self::PAID : self::UNPAID;
        });
    }


    public static function searchable()
    {
        return ['name'];
    }

    protected static function newFactory()
    {
        return SaleReturnFactory::new();
    }
}
