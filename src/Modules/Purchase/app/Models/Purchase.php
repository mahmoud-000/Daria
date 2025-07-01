<?php

namespace Modules\Purchase\Models;

use App\Enums\FPTypesEnum;
use App\Enums\PaymentStatusEnum;
use App\Traits\BaseInvoiceRelationsTrait;
use App\Traits\InvoiceQrCodeTrait;
use App\Traits\InvoiceSupplierTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Delegate\Models\Delegate;
use Modules\Purchase\Database\Factories\PurchaseFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Payment\Models\Payment;

class Purchase extends Model implements HasMedia
{
    use HasFactory,
        Searchable,
        SoftDeletes,
        InteractsWithMedia,
        BaseInvoiceRelationsTrait,
        InvoiceSupplierTrait,
        InvoiceQrCodeTrait;

    protected $withCount = ['media'];

    protected $fillable = [
        'user_id',
        'supplier_id',
        'warehouse_id',
        'pipeline_id',
        'stage_id',
        'effected',
        'discount_type',
        'discount',
        'remarks',
        'date',
        'tax',
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
        'effected'          => 'boolean',
        'payment_status'    => PaymentStatusEnum::class,
        'discount_type'     => FPTypesEnum::class,
        'commission_type'   => FPTypesEnum::class,
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
        if (floatval($model->paid_amount) < floatval($model->grand_total) && floatval($model->paid_amount) !== 0.0) {
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

    public function tax(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public function discount(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public function paidAmount(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public function grandTotal(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public function shipping(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public function otherExpenses(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public static function searchable()
    {
        return ['date'];
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function delegate()
    {
        return $this->belongsTo(Delegate::class)->withTrashed();
    }

    protected static function newFactory()
    {
        return PurchaseFactory::new();
    }
}
