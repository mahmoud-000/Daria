<?php

namespace Modules\SaleReturn\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Traits\InvoiceTrait;
use Modules\Purchase\Http\Services\PurchaseService;

class SaleReturnService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::SALE_RETURN->value;

    public function calcQte($invoice, $detail, $isComplete)
    {
        return PurchaseService::calcQte($invoice, $detail, $isComplete);
    }

    public static function calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable)
    {
        return PurchaseService::calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable);
    }

    public function updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete)
    {
        return PurchaseService::updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete);
    }

    public function destroyDetails($invoice, $deletedDetails)
    {
        return PurchaseService::destroyDetails($invoice, $deletedDetails); 
    }
}
