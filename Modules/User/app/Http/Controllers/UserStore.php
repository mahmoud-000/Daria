<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\CreateUserNotification;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Modules\Role\Models\Role;
use Modules\Setting\Models\Setting;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\User\Models\User;
use Modules\User\Http\Requests\StoreUserRequest;

class UserStore extends Controller
{
    public function __invoke(StoreUserRequest $request)
    {
        try {
            $request = $request->validated();
            $user = DB::transaction(function () use ($request) {
                $user = User::create(Arr::except($request, ['contacts', 'locations', 'avatar', 'permissions', 'role_ids']));
                
                if (isset($request['role_ids'])) {
                    $permissionsInRole = Role::with('permissions')->whereIn('id', $request['role_ids'])->get()->pluck('permissions')->flatten()->pluck('name')->toArray();;
                    $extraPermissions = array_diff($request['permissions'], $permissionsInRole);
                    
                    $user->givePermissionsTo($extraPermissions);
    
                    $user->roles()->attach($request['role_ids']);
                } else {
                    $user->roles()->detach();
                    if (isset($request['permissions'])) {
                        $user->givePermissionsTo($request['permissions']);
                    }
                }

                if (isset($request['contacts']) && count($request['contacts'])) {
                    $user->contacts()->createMany($request['contacts']);
                }

                if (isset($request['locations']) && count($request['locations'])) {
                    $user->locations()->createMany($request['locations']);
                }

                if (isset($request['avatar']) && !is_null($request['avatar']) && !array_key_exists('fake', $request['avatar'])) {
                    (new FilesAssign)($request['avatar'], $user, 'users', 'avatar');
                }
                
                if (isset($request['send_notify']) && $request['send_notify'] === true  && Setting::checkConfigMail()) {
                    Notification::send($user, new CreateUserNotification());
                }
                return $user;
            });
            return $this->success(__('status.created', ['name' => $user['username'], 'module' => __('modules.user')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
