<?php

namespace Modules\Department\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Branch\Models\Branch;
use Modules\Company\Models\Company;
use Modules\Department\Database\Factories\DepartmentFactory;

class Department extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'remarks',
        'is_active',
        'department_id',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
        'department_id'       => 'integer',
    ];

    public static function searchable()
    {
        return ['name'];
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function sub()
    {
        return $this->hasOne(Department::class);
    }

    protected static function newFactory()
    {
        return DepartmentFactory::new();
    }
}
