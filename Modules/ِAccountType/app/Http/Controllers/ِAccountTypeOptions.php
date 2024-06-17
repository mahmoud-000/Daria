<?php

namespace Modules\ِAccountType\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ِAccountType\Transformers\ِAccountTypeResource;
use Modules\ِAccountType\Models\ِAccountType;

class ِAccountTypeOptions extends Controller
{
    public function __invoke()
    {
        return ِAccountTypeResource::collection(ِAccountType::where('is_active', true)->get());
    }
}
