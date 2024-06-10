<?php

namespace Modules\Attribute\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Attribute\Database\Factories\AttributeFactory;

class Attribute extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
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
        return AttributeFactory::new();
    }
}
