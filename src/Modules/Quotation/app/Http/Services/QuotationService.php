<?php

namespace Modules\Quotation\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Traits\InvoiceTrait;

class QuotationService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::QUOTATION->value;

    public static function calcQte($invoice, $detail, $isComplete)
    {
        // return self::stockyByUnit($detail['unit_id'], $detail['quantity']);
        return 0;
    }

    public static function calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable)
    {
        // return self::stockyByUnit($detail['unit_id'], $oldDetail['quantity']);
        return 0;
    }

    public static function updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete)
    {
        return false;
    }
    public static function destroyDetails($invoice, $deletedDetails)
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
