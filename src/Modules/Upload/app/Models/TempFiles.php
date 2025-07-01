<?php

namespace Modules\Upload\Models;

use Illuminate\Database\Eloquent\Model;

class TempFiles extends Model
{
    protected $fillable = ['folder', 'filename', 'ext'];

    protected $table = "temporary_files";
}

