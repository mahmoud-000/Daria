<?php

namespace Modules\Quotation\Models;

use App\Enums\PaymentStatusEnum;
use App\Traits\BaseInvoiceRelationsTrait;
use App\Traits\InvoiceQrCodeTrait;
use App\Traits\InvoiceCustomerTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Delegate\Models\Delegate;
use Modules\Quotation\Database\Factories\QuotationFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Quotation extends Model implements HasMedia
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
        'grand_total',
        'delegate_id',
        'commission_type',
        'shipping',
        'other_expenses',
        'doc_invoice_number',
    ];

    protected $casts = [
        'effected' => 'boolean',
        'discount_type' => \App\Enums\FPTypesEnum::class,
        'commission_type' => \App\Enums\FPTypesEnum::class,

        'tax' => 'double',
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
        });
    }


    public static function searchable()
    {
        return ['date'];
    }

    public function delegate()
    {
        return $this->belongsTo(Delegate::class)->withTrashed();
    }

    protected static function newFactory()
    {
        return QuotationFactory::new();
    }
}
