<?php

namespace Modules\Comment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Comment\Database\Factories\CommentFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'text', 'commentable_id', 'commentable_type',  'userable_id', 'userable_type',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function userable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return CommentFactory::new();
    }
}
