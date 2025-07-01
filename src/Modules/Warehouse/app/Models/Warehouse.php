<?php

namespace Modules\Warehouse\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Warehouse\Database\Factories\WarehouseFactory;

class Warehouse extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'mobile',
        'zip',
        'country',
        'city',
        'state',
        'first_address',
        'second_address',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
    ];

    public static function searchable()
    {
        return ['name'];
    }

    protected static function newFactory()
    {
        return WarehouseFactory::new();
    }
}
