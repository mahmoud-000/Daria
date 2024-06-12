<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Role\Models\Role;
use Illuminate\Support\Str;

class RoleImportCsv extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('import-csv-role'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $roles = $request->all();
            $removed = array_shift($roles);
            $rolesArray = [];
            foreach ($roles as $key => $value) {
                $rolesArray[] = [
                    'name' => $value['name'],
                    'email' => isset($value['email']) && filter_var($value['email'], FILTER_VALIDATE_EMAIL) ? $value['email'] : null,
                    'firstname' => $value['firstname'],
                    'lastname' => $value['lastname'],
                    'date_of_birth' => isset($value['date_of_birth']) && (bool)strtotime($value['date_of_birth']) ? $value['date_of_birth'] : null,
                    'date_of_joining' => isset($value['date_of_joining']) && (bool)strtotime($value['date_of_joining']) ? $value['date_of_joining'] : null,
                    'gender' => isset($value['gender']) && Str::lower($value['gender']) == Controller::MALE ? Controller::GENDER_MALE : Controller::GENDER_FEMALE,
                    'is_active' => isset($value['is_active']) && in_array(Str::lower($value['is_active']), Controller::ACTIVE_ARRAY) ? true : false,
                    'password' => isset($value['password']) ? $value['password'] : 'DefaultPassword1@'
                ];
            }
            $errors = [];
            foreach ($rolesArray as $role) {
                $validator = Validator::make($role, [
                    'name'  => ['required', 'string', 'min:8', 'max:100', Rule::unique('roles', 'name')->withoutTrashed()],
                    'password'  => ['required', Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()],
                    'email'     => ['nullable', 'email', Rule::unique('roles', 'email')->withoutTrashed()],
                    'firstname' => ['nullable', 'string', 'min:3', 'max:50'],
                    'lastname'  => ['nullable', 'string', 'min:3', 'max:50'],

                    'gender'        => ['nullable', 'integer'],
                    'date_of_birth' => ['nullable', 'string'],
                    'date_of_joining' => ['nullable', 'string'],
                    'is_active'     => ['required', 'boolean'],
                    'remarks'       => ['string', 'nullable'],
                ]);

                if ($validator->fails()) {
                    $errors[$role['name']] = $validator->errors();
                }
            }
            if ($errors) {
                return $this->error(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            Role::upsert($rolesArray, ['email']);
            return $this->success(__('status.imported_csv_successfully'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
