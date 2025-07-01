<?php

namespace Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Contact\Database\Factories\ContactFactory;

class Contact extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'mobile', 'phone', 'contactable_id', 'contactable_type'
    ];

    public function contactable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return ContactFactory::new();
    }
}
