<?php

namespace Modules\Quotation\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Traits\InvoiceTrait;

class QuotationService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::QUOTATION->value;

    public function calcQte($detail, $isComplete, $quantityInDBTable)
    {
        return $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
    }

    public function calcUpdatedQte($oldQuotationEffected, $detail, $oldDetail, $isComplete, $old_isComplete, $quantityInDBTable)
    {
        return $this->stockyByUnit($detail['unit_id'], $oldDetail['quantity']);
    }

    public function destroyDetails($invoice, $deletedDetails, $old_isComplete)
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
