<?php

namespace Modules\Sale\Models;

use App\Enums\PaymentStatusEnum;
use App\Traits\BaseInvoiceRelationsTrait;
use App\Traits\InvoiceQrCodeTrait;
use App\Traits\InvoiceCustomerTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Delegate\Models\Delegate;
use Modules\Sale\Database\Factories\SaleFactory;
use Modules\User\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Sale extends Model implements HasMedia
{
    use HasFactory,
        Searchable,
        SoftDeletes,
        InteractsWithMedia,
        BaseInvoiceRelationsTrait,
        InvoiceCustomerTrait,
        InvoiceQrCodeTrait;

    protected $withCount = ['media'];

        protected $fillable = [
        'user_id',
        'customer_id',
        'warehouse_id',
        'pipeline_id',
        'stage_id',
        'effected',
        'discount_type',
        'discount',
        'remarks',
        'date',
        'tax',
        // 'tax_net',
        'paid_amount',
        'payment_status',
        'grand_total',
        'delegate_id',
        'commission_type',
        'shipping',
        'other_expenses',
        'doc_invoice_number',
    ];

    protected $casts = [
        'effected' => 'boolean',
        'payment_status' => \App\Enums\PaymentStatusEnum::class,
        'discount_type' => \App\Enums\FPTypesEnum::class,
        'commission_type' => \App\Enums\FPTypesEnum::class,

        'tax' => 'double',
        // 'tax_net' => 'double',
        'paid_amount' => 'double',
        'grand_total' => 'double',
        'discount' => 'double',
        'shipping' => 'double',
        'other_expenses' => 'double',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = auth()->id();
            static::paymentStatus($model);
        });
        
        static::updating(function ($model) {
            static::paymentStatus($model);
        });
    }

    public static function paymentStatus($model)
    {
        if(floatval($model->paid_amount) < floatval($model->grand_total) && floatval($model->paid_amount) !== 0.0) {
            $model->payment_status = PaymentStatusEnum::PARTIAL;
        } elseif (floatval($model->paid_amount) === floatval($model->grand_total)) {
            $model->payment_status = PaymentStatusEnum::PAID;
        } elseif (floatval($model->paid_amount) < floatval($model->grand_total) && floatval($model->paid_amount) === 0.0) {
            $model->payment_status = PaymentStatusEnum::UNPAID;
        } else {
            $model->payment_status = PaymentStatusEnum::UNPAID;
        }

        return $model->payment_status;
    }

    public static function searchable()
    {
        return ['date'];
    }

    public function delegate()
    {
        return $this->belongsTo(Delegate::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    protected static function newFactory()
    {
        return SaleFactory::new();
    }
}
