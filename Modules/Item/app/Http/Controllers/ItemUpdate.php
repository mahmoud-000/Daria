<?php

namespace Modules\Item\Http\Controllers;

use App\Enums\ItemTypesEnum;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Item\Models\Item;
use Modules\Item\Http\Requests\UpdateItemRequest;
use Modules\Upload\Http\Controllers\FilesAssign;
use Modules\Variant\Http\Controllers\VariantUpdate;

class ItemUpdate extends Controller
{
    public function __invoke(UpdateItemRequest $request, Item $item)
    {
        try {
            $request = $request->validated();
            $item = DB::transaction(function () use ($item, $request) {
                $item->update(Arr::except($request, ['item_images', 'variants']));
                
                if (isset($request['item_images']) && !is_null($request['item_images']) && !array_key_exists('fake', $request['item_images'])) {
                    (new FilesAssign)($request['item_images'], $item, 'items', 'item_images', true);
                }
                
                $variants = isset($request['variants']) ? $request['variants'] : [];
                // Check if item has variants
                if ($item->type === ItemTypesEnum::VARIABLE) {
                    $variants = (new VariantUpdate)($item, $variants);
                }
               
                return $item;
            });
            return $this->success(__('status.updated', ['name' => $item['name'], 'module' => __('modules.item')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
