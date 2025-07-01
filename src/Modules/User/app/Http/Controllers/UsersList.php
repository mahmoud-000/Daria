<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Modules\User\Transformers\UsersCollectionResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class UsersList extends Controller
{
    public function __invoke(Request $req)
    {
        if (!auth()->user()->is_owner)  abort_if(!Gate::any(['list-user', auth()->user()->is_owner]), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        $dir = $req->descending === 'true' ? 'desc' : 'asc';
        return UsersCollectionResource::collection(
            User::query()
                ->select(['id', 'email', 'username', 'firstname', 'lastname', 'is_active', 'created_at', 'updated_at'])
                ->with(['media'])
                ->withCount(['media'])
                ->where('is_owner', '!=', true)
                ->where('id', '!=', auth()->id())
                ->search($req->filter)
                ->orderBy($req->sortBy, $dir)
                ->paginate($req->rowsPerPage)
        );
    }
}
