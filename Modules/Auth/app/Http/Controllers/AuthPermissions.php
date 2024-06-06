<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;

class AuthPermissions extends Controller
{
  public function __invoke()
  {
    
    $user = Auth()->user()->load(['roles:id,name', 'roles.permissions:id,name', 'permissions:id,name'])->select('id')->first();

    return response()->json($user->getAllPermissions());
  }
}
