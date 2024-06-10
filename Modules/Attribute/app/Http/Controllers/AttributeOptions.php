<?php

namespace Modules\Attribute\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Attribute\Transformers\AttributeResource;
use Modules\Attribute\Models\Attribute;

class AttributeOptions extends Controller
{
    public function __invoke()
    {
        return AttributeResource::collection(Attribute::where('is_active', true)->get());
    }
}
