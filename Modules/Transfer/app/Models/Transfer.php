<?php

namespace Modules\Transfer\Models;

use App\Traits\InvoiceQrCodeTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Delegate\Models\Delegate;
use Modules\Detail\Models\Detail;
use Modules\Pipeline\Models\Pipeline;
use Modules\Stage\Models\Stage;
use Modules\Transfer\Database\Factories\TransferFactory;
use Modules\User\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Warehouse\Models\Warehouse;

class Transfer extends Model implements HasMedia
{
    use HasFactory,
        Searchable,
        SoftDeletes,
        InteractsWithMedia,
        InvoiceQrCodeTrait;

    protected $withCount = ['media'];

    protected $fillable = [
        'user_id',
        'from_warehouse_id',
        'to_warehouse_id',
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

    public function warehouseFrom()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id')->withTrashed();
    }

    public function warehouseTo()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id')->withTrashed();
    }

    public function details()
    {
        return $this->morphMany(Detail::class, 'detailable');
    }

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class)->withTrashed();
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function delegate()
    {
        return $this->belongsTo(Delegate::class)->withTrashed();
    }

    protected static function newFactory()
    {
        return TransferFactory::new();
    }
}
