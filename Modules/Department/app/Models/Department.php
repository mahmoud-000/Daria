<?php

namespace Modules\Department\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Department\Database\Factories\DepartmentFactory;

class Department extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'remarks',
        'is_active',
        'company_id',
        'branch_id',
        'department_id',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
        'company_id'       => 'integer',
        'branch_id'       => 'integer',
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
