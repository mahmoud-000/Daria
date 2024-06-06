<?php

namespace Modules\Variant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Http\Controllers\StockDestroy;
use Modules\Stock\Http\Controllers\StockStore;
use Modules\Variant\Models\Variant;

class VariantUpdate extends Controller
{
    public function __invoke($item, $variants)
    {
        DB::transaction(function () use ($item, $variants) {
            $existingVariantsIds = [];
            $existingVariants = [];
            $newVariantsArray = [];
            foreach ($variants as $variant) {
                // Check if the variant has id or not
                // If not, create a new variant
                // If has id, update the existing variant
                if (isset($variant['id'])) {
                    $variant['item_id'] = $item->id;
                    $existingVariants[] = $variant;
                    $existingVariantsIds[] = $variant['id'];
                } else {
                    $newVariantsArray[] = $variant;
                }
            }

            // If has variants to update
            if (count($existingVariantsIds)) {
                // Get the ids of the not existing variants
                $variantsDeletedIds = Variant::whereItemId($item->id)
                    ->whereNotIn('id', $existingVariantsIds)
                    ->pluck('id')
                    ->toArray();
                // Update the existing variants
                Variant::upsert($existingVariants, ['id']);
                // Delete the not existing variants
                Variant::whereIn('id', $variantsDeletedIds)
                    ->delete();
                // Delete the stock for the not existing variants
                (new StockDestroy)($item, $variantsDeletedIds);
            }
            // Create new variants if not empty
            $newVariantsCreated = [];
            if (count($newVariantsArray)) {
                $newVariantsCreated = (new VariantStore)($item, $newVariantsArray);
            }
            // After Create new variants
            // Also create stock for new variants
            if (count($newVariantsCreated)) {
                (new StockStore)($item, $newVariantsCreated);
            }
        });
    }
}
