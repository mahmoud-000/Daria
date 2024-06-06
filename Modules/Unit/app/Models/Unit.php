<?php

namespace Modules\Unit\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Unit\Database\Factories\UnitFactory;

class Unit extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'short_name',
        'unit_id',
        'operator',
        'operator_value',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
        'operator_value'    => 'decimal:2'
    ];

    public static function searchable()
    {
        return ['name'];
    }

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function sub()
    {
        return $this->hasOne(Unit::class, 'unit_id');
    }

    protected static function newFactory()
    {
        return UnitFactory::new();
    }
}
