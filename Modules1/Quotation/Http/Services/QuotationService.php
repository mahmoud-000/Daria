<?php

namespace Modules\Quotation\Http\Services;

use App\Interface\Defination;
use App\Traits\InvoiceTrait;

class QuotationService implements Defination
{
    use InvoiceTrait;

    public function calculateStocky($detail, $is_won)
    {
        return $this->stockyWithUnit($detail['unit_id'], $detail['stocky']);
    }

    public function claculateUpdateStocky($oldPurchaseEffected, $detail, $oldDetail, $is_won, $old_is_won)
    {
        return $this->stockyWithUnit($detail['unit_id'], $detail['stocky']);
    }

    public function destroyDetails($quotation, $deletedDetails)
    {

        $deletedIds = [];
        foreach ($deletedDetails as $deletedDetail) {
            if (isset($deletedDetail['id'])) {
                $deletedIds[] = $deletedDetail['id'];
            }
        }
        $quotation->details()->whereIn('id', $deletedIds)->delete();
    }
}
