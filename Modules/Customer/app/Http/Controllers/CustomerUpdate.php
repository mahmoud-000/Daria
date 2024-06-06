<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Contact\Models\Contact;
use Modules\Location\Models\Location;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\Customer\Models\Customer;
use Modules\Customer\Http\Requests\UpdateCustomerRequest;

class CustomerUpdate extends Controller
{
    public function __invoke(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $request = $request->validated();

            $customer = DB::transaction(function () use ($customer, $request) {
                $customer->update(Arr::except($request, ['contacts', 'locations', 'avatar']));

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

                    $customer->contacts()->whereNotIn('id', $ids)->delete();

                    if ($create_contacts) {
                        $customer->contacts()->createMany($create_contacts);
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

                    $customer->locations()->whereNotIn('id', $ids)->delete();

                    if ($create_locations) {
                        $customer->locations()->createMany($create_locations);
                    }
                    if ($locations) {
                        Location::upsert($locations, ['id']);
                    }
                }

                if (isset($request['avatar']) && !is_null($request['avatar']) && !array_key_exists('fake', $request['avatar'])) {
                    (new FilesAssign)($request['avatar'], $customer, 'customers', 'avatar');
                }

                return $customer;
            });

            return $this->success(__('status.updated', ['name' => $customer['fullname'], 'module' => __('modules.customer')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
