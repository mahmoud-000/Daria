<?php

namespace Modules\ِAccountType\Http\Controllers;

use App\Enums\ItemTypesEnum;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Modules\Item\Models\Item;
use Modules\Stock\Models\Stock;
use Modules\ِAccountType\Models\ِAccountType;
use Modules\ِAccountType\Http\Requests\StoreِAccountTypeRequest;

class ِAccountTypeStore extends Controller
{
    public function __invoke(StoreِAccountTypeRequest $request)
    {
        try {
            $ِaccountType = DB::transaction(function () use ($request) {
                $ِaccountType = ِAccountType::create($request->validated());

                $items = Item::with('variants:id,item_id')->where('deleted_at', '=', null)
                    ->get(['id', 'type'])
                    ->toArray();
                if (count($items)) {
                    $stock = [];
                    foreach ($items as $item) {
                        if ($item['type'] === ItemTypesEnum::VARIABLE->value) {
                            $variants = $item['variants'];
                            if (count($variants)) {
                                foreach ($variants as $variant) {
                                    $stock[] = [
                                        'item_id' => $item['id'],
                                        'ِaccountType_id' => $ِaccountType->id,
                                        'variant_id' => $variant['id'],
                                    ];
                                }
                            }
                        } else {
                            $stock[] = [
                                'item_id' => $item['id'],
                                'ِaccountType_id' => $ِaccountType->id,
                                'variant_id' => null,
                            ];
                        }
                    }
                    Stock::insert($stock);
                }
                return $ِaccountType;
            });
            return $this->success(__('status.created', ['name' => $ِaccountType['name'], 'module' => __('modules.ِaccountType')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
