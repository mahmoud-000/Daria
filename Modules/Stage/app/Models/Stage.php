<?php

namespace Modules\Stage\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pipeline\Models\Pipeline;
use Modules\Stage\Database\Factories\StageFactory;

class Stage extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'color',
        'complete',
        'pipeline_id',
        'is_default',
        'is_active',
        'remarks',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
        'is_default'         => 'boolean',
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
        return StageFactory::new();
    }
}
