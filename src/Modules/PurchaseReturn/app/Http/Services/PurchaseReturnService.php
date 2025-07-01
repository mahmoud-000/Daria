<?php

namespace Modules\PurchaseReturn\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Traits\InvoiceTrait;
use Modules\Sale\Http\Services\SaleService;

class PurchaseReturnService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::PURCHASE_RETURN->value;
    
    public static function calcQte($invoice, $detail, $isComplete)
    {
        return SaleService::calcQte($invoice, $detail, $isComplete);
    }

    public function calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable)
    {
        return SaleService::calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable);
    }

    public static function updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete)
    {
        return SaleService::updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete);
    }

    public static function destroyDetails($invoice, $deletedDetails)
    {
        return SaleService::destroyDetails($invoice, $deletedDetails);
    }
}
