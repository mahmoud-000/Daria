<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\CreateUserNotification;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Modules\Contact\Models\Contact;
use Modules\Location\Models\Location;
use Modules\Role\Models\Role;
use Modules\Setting\Models\Setting;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\User\Models\User;
use Modules\User\Http\Requests\UpdateUserRequest;

class UserUpdate extends Controller
{
    public function __invoke(UpdateUserRequest $request, User $user)
    {
        try {
            $request = $request->validated();

            $user = DB::transaction(function () use ($user, $request) {
                $user->update(Arr::except($request, ['contacts', 'locations', 'avatar', 'roles', 'permissions']));
                if (isset($request['roles'])) {
                    $permissionsInRole = Role::with('permissions')->whereIn('id', $request['roles'])->get()->pluck('permissions')->flatten()->pluck('name')->toArray();;
                    $extraPermissions = array_diff($request['permissions'], $permissionsInRole);

                    $user->setPermissions($extraPermissions);

                    $user->roles()->sync($request['roles']);
                } else {
                    $user->roles()->detach();
                    
                    if (isset($request['permissions'])) {
                        $user->setPermissions($request['permissions']);
                    }
                }

                if (isset($request['contacts'])) {
                    $contacts = [];
                    $create_contacts = [];
                    $ids = [];

                    foreach ($request['contacts'] as $contact) {
                        if (isset($contact['id'])) {
                            $ids[] = $contact['id'];
                            $contacts[] = $contact;
                        } else {
                            if (!is_null($contact['mobile']) || !is_null($contact['phone']) || !is_null($contact['email'])) {
                                $create_contacts[] = $contact;
                            }
                        }
                    }

                    $user->contacts()->whereNotIn('id', $ids)->delete();

                    if ($create_contacts) {
                        $user->contacts()->createMany($create_contacts);
                    }

                    if ($contacts) {
                        Contact::upsert($contacts, ['id']);
                    }
                }

                if (isset($request['locations'])) {
                    $locations = [];
                    $create_locations = [];
                    $ids = [];

                    foreach ($request['locations'] as $location) {
                        if (isset($location['id'])) {
                            $ids[] = $location['id'];
                            $locations[] = $location;
                        } else {
                            if (!is_null($location['country']) || !is_null($location['city']) || !is_null($location['state']) || !is_null($location['zip']) || !is_null($location['first_address']) || !is_null($location['second_address'])) {
                                $create_locations[] = $location;
                            }
                        }
                    }

                    $user->locations()->whereNotIn('id', $ids)->delete();

                    if ($create_locations) {
                        $user->locations()->createMany($create_locations);
                    }
                    if ($locations) {
                        Location::upsert($locations, ['id']);
                    }
                }

                if (isset($request['avatar']) && !is_null($request['avatar']) && !array_key_exists('fake', $request['avatar'])) {
                    (new FilesAssign)($request['avatar'], $user, 'users', 'avatar');
                }

                if (isset($request['send_notify']) && $request['send_notify'] === true  && Setting::checkConfigMail()) {
                    Notification::send($user, new CreateUserNotification());
                }
                return $user;
            });

            return $this->success(__('status.updated', ['name' => $user['username'], 'module' => __('modules.user')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
