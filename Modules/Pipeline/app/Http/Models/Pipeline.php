<?php

namespace Modules\Pipeline\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pipeline\Database\Factories\PipelineFactory;
use Modules\Stage\Models\Stage;

class Pipeline extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'app_name',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
    ];

    public function stages()
    {
        return $this->hasMany(Stage::class)->orderBy('complete', 'asc');
    }

    public static function searchable()
    {
        return ['name'];
    }

    protected static function newFactory()
    {
        return PipelineFactory::new();
    }
}
