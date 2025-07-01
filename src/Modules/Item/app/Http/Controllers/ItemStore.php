<?php

namespace Modules\Item\Http\Controllers;

use App\Enums\ItemTypesEnum;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Item\Models\Item;
use Modules\Item\Http\Requests\StoreItemRequest;
use Modules\Stock\Http\Controllers\StockStore;
use Modules\Upload\Http\Controllers\FilesAssign;

class ItemStore extends Controller
{
    public function __invoke(StoreItemRequest $request)
    {
        try {
            $request = $request->validated();
            $item = DB::transaction(function () use ($request) {
                $item = Item::create(Arr::except($request, ['item_images', 'variants']));

                if (isset($request['item_images']) && !is_null($request['item_images']) && !array_key_exists('fake', $request['item_images'])) {
                    (new FilesAssign)($request['item_images'], $item, 'items', 'item_images', true);
                }

                $variants = isset($request['variants']) ? $request['variants'] : [];
                $newVariants = [];
                // Check if item has variants
                if ($request['type'] === ItemTypesEnum::VARIABLE->value && count($variants)) {;
                    $newVariants = $item->variants()->createMany($variants);
                }

                (new StockStore)($item, $newVariants);

                return $item;
            });
            return $this->success(__('status.created', ['name' => $item['name'], 'module' => __('modules.item')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
