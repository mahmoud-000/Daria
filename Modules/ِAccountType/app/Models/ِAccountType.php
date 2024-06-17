<?php

namespace Modules\ِAccountType\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ِAccountType\Database\Factories\ِAccountTypeFactory;

class ِAccountType extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $table = "accoun_types";

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
        return ِAccountTypeFactory::new();
    }
}
