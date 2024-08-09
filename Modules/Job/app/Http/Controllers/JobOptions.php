<?php

namespace Modules\Job\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Job\Transformers\JobResource;
use Modules\Job\Models\Job;

class JobOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return JobResource::collection(
            Job::query()
            ->with('media')
            ->where('is_active', ActiveEnum::ACTIVED->value)
            ->when(!empty($req->search), fn ($query) => $query->where('name', 'LIKE', '%' . $req->search . '%'))
            ->paginate(10));
    }
}
