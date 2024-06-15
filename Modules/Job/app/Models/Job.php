<?php

namespace Modules\Job\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Job\Database\Factories\JobFactory;

class Job extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $table = "job_titles";
    
    protected $fillable = [
        'title',
        'min_salary',
        'max_salary',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
        'min_salary'        => 'double',
        'max_salary'        => 'double',
    ];

    public static function searchable()
    {
        return ['title'];
    }

    protected static function newFactory()
    {
        return JobFactory::new();
    }
}
