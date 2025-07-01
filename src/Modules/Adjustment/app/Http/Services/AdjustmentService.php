<?php

namespace Modules\Adjustment\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Traits\InvoiceTrait;
use Modules\Purchase\Http\Services\PurchaseService;
use Modules\Sale\Http\Services\SaleService;

class AdjustmentService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::ADJUSTMENT->value;
    const ADDITION = 1;
    const SUBSTRACTION = 2;

    public static function calcQte($invoice, $detail, $isComplete)
    {

        if ($detail['movement'] === self::ADDITION) {
            return PurchaseService::calcQte($invoice, $detail, $isComplete);
        }

        if ($detail['movement'] === self::SUBSTRACTION) {
            return SaleService::calcQte($invoice, $detail, $isComplete);
        }
    }

    public static function calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable)
    {
        if ($oldDetail['movement'] === self::ADDITION) {
            return PurchaseService::calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable);
        }

        if ($oldDetail['movement'] === self::SUBSTRACTION) {
            return SaleService::calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable);
        }
    }

    public static function updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete)
    {
        return PurchaseService::updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete);
    }


    public function destroyDetails($invoice, $deletedDetails)
    {
        $addDetails = [];
        $subDetails = [];
        if (count($deletedDetails)) {
            foreach ($deletedDetails as $deletedDetail) {
                if ($deletedDetail['movement'] === self::ADDITION) {
                    $addDetails[] = $deletedDetail;
                }
                if ($deletedDetail['movement'] === self::SUBSTRACTION) {
                    $subDetails[] = $deletedDetail;
                }
            }
        }
        if (count($addDetails)) {
            return PurchaseService::destroyDetails($invoice, $addDetails);
        }

        if (count($subDetails)) {
            return SaleService::destroyDetails($invoice, $subDetails);
        }
    }
}
