<?php

namespace Modules\Adjustment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Warehouse\Http\Controllers\WarehouseOptions;
use Modules\Unit\Http\Controllers\UnitOptions;
use Modules\Pipeline\Http\Controllers\PipelineOptions;
use Modules\Setting\Models\Setting;
use Modules\Setting\Transformers\SettingResource;

class AdjustmentFormOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return [
            'warehouses' => (new WarehouseOptions)(),
            'units' => (new UnitOptions)(),
            'pipelines' => (new PipelineOptions)($req),
            'settings' => SettingResource::collection(Setting::systemOnly()->get()),
        ];
    }
}
