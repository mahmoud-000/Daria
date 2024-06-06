<?php

namespace Modules\User\Models;

use App\Notifications\ResetPasswordNotification;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Modules\Contact\Models\Contact;
use Modules\Location\Models\Location;
use Modules\Permission\Traits\HasPermissions;
use Modules\Setting\Models\Setting;
use Modules\User\Database\Factories\UserFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, Searchable, HasPermissions, SoftDeletes, InteractsWithMedia;

    protected $guard = 'api';
    
    protected $withCount = ['media'];
    
    protected $fillable = [
        'username',
        'password',
        'email',
        'firstname',
        'lastname',
        'date_of_birth',
        'date_of_joining',
        'remarks',
        'is_active',
        'send_notify',
        'gender',
        'is_owner',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => \App\Enums\ActiveEnum::class,
        'send_notify'       => 'boolean',
        'is_owner'          => 'boolean',
        'gender'            => 'integer',
        'date_of_birth'     => 'immutable_date',
        'date_of_joining'   => 'immutable_date',
    ];
    
    public static function searchable()
    {
        return ['firstname', 'lastname', 'username', 'email'];
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function locations()
    {
        return $this->morphMany(Location::class, 'locationable');
    }

    public function rawSettings(): HasMany
    {
        return $this->hasMany(Setting::class, 'user_id', 'id');
    }

    public function settings(): Collection
    {
        if ($this->rawSettings->isEmpty()) {
            $this->load('rawSettings');
        }

        return $this->rawSettings->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        });
    }

    public function setPasswordAttribute($password)
    {
        if ($password !== null) {
            return $this->attributes['password'] = Hash::make($password);
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
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
        return UserFactory::new();
    }
}
