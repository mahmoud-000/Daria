<?php

namespace Modules\Delegate\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Contact\Models\Contact;
use Modules\Location\Models\Location;
use Modules\Delegate\Database\Factories\DelegateFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Delegate extends Authenticatable implements HasMedia
{
    use HasFactory, Searchable, SoftDeletes, InteractsWithMedia;
    
    protected $withCount = ['media'];
    
    protected $fillable = [
        'fullname',
        'company_name',
        'email',
        'remarks',
        'is_active',
        'commission',
        'commission_type',
        'type'
    ];

    protected $casts = [
        'is_active'         => \App\Enums\ActiveEnum::class,
        'type'              => \App\Enums\ICTypesEnum::class,
        'commission_type'   => \App\Enums\FPTypesEnum::class,
        'commission'              => 'double',
    ];
    
    public static function searchable()
    {
        return ['fullname', 'email', 'company_name'];
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function locations()
    {
        return $this->morphMany(Location::class, 'locationable');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->width(36)
            ->height(36)
            ->nonQueued();
    }

    protected static function newFactory()
    {
        return DelegateFactory::new();
    }
}
