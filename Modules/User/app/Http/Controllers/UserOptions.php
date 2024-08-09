<?php

namespace Modules\User\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Modules\User\Transformers\UsersCollectionResource;

class UserOptions extends Controller
{
    public function __invoke(Request $req)
    {
        return UsersCollectionResource::collection(
            User::query()
                ->with('media')
                ->where('is_active', ActiveEnum::ACTIVED->value)
                ->when(!empty($req->search), fn($query) => $query->where('username', 'LIKE', '%' . $req->search . '%'))
                ->paginate(10)
        );
    }
}
