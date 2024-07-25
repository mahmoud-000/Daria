<?php

namespace Modules\Quotation\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Traits\InvoiceTrait;

class QuotationService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::QUOTATION->value;

    public function calcQte($invoice, $detail, $isComplete)
    {
        return $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
    }

    public function calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable)
    {
        return $this->stockyByUnit($detail['unit_id'], $oldDetail['quantity']);
    }

    public function updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete)
    {
        return false;
    }
    public function destroyDetails($invoice, $deletedDetails)
    {
        $deletedIds = [];

        if (count($deletedDetails)) {
            foreach ($deletedDetails as $deletedDetail) {
                if (isset($deletedDetail['id'])) {
                    $deletedIds[] = $deletedDetail['id'];
                }
            }
            $invoice->details()->whereIn('id', $deletedIds)->delete();
        }
    }
}
