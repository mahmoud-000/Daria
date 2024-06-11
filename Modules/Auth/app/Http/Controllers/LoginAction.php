<?php

namespace Modules\Auth\Http\Controllers;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\AuthRequest;
use Modules\Auth\Transformers\AuthResource;
use Modules\User\Models\User;

class LoginAction extends Controller
{
  public function __invoke(AuthRequest $request)
  {
    $username = $request->input('username');
    $password = $request->input('password');
    $remember = $request->input('remember');

    $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $user = User::query()
      ->with(['roles:id,name', 'roles.permissions:id,name', 'permissions:id,name', 'media'])
      ->where($field, $username)
      ->first();
    // $user->sendEmailVerificationNotification();
    if (
      !$user || !Hash::check($password, $user->password)
      || !Auth::guard('api')->attempt(
        [
          $field => $username,
          'password' => $password
        ],
        $remember
      )
    ) {
      return $this->error(['message' => __('auth::auth.failed')], Response::HTTP_UNAUTHORIZED);
    }

    if ($user && $user->is_active !== ActiveEnum::ACTIVED) return $this->error(['message' => trans('auth::auth.account_not_active')], Response::HTTP_UNAUTHORIZED);

    $token = $user->createToken($request->device)->plainTextToken;

    return $this->success([
        'message' => trans('auth::auth.login', ['user' => $user->username]), 
        'token' => $token, 
        'user' => AuthResource::make($user), 
        'permissions' => $user->getAllPermissions()
    ]);
  }
}
