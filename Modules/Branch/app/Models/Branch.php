<?php

namespace Modules\Branch\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pipeline\Models\Pipeline;
use Modules\Branch\Database\Factories\BranchFactory;

class Branch extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'address',
        'country',
        'city',
        'state',
        'zip',
        'phone',
        'mobile',
        'is_active',
        'is_main',
        'company_id',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
        'is_main'           => 'boolean',
    ];

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }

    public static function searchable()
    {
        return ['name'];
    }
    protected static function newFactory()
    {
        return BranchFactory::new();
    }
}
