<?php

namespace Modules\Company\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Company\Database\Factories\CompanyFactory;
use Modules\Branch\Models\Branch;

class Company extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'module_name',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
    ];

    public function branches()
    {
        return $this->hasMany(Branch::class)->orderBy('complete', 'asc');
    }

    public static function searchable()
    {
        return ['name'];
    }

    protected static function newFactory()
    {
        return CompanyFactory::new();
    }
}
