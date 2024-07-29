<?php

namespace Modules\Transfer\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Enums\ProductTypesEnum;
use App\Traits\InvoiceTrait;
use Modules\Purchase\Http\Services\PurchaseService;
use Modules\Sale\Http\Services\SaleService;

class TransferService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::TRANSFER->value;
    const FROM = 'FROM';
    const TO = 'TO';

    public static function calcQte($invoice, $detail, $isComplete)
    {
        if ($isComplete) {
            $quantityInDBTableFrom = self::qteStockInDB($invoice['from_warehouse_id'], $detail);
            $quantityInDBTableTo = self::qteStockInDB($invoice['to_warehouse_id'], $detail);
            
            $quantityInDBTableFrom -= self::stockyByUnit($detail['unit_id'], $detail['quantity']);
            $quantityInDBTableTo += self::stockyByUnit($detail['unit_id'], $detail['quantity']);
            
            $stockFrom = self::updateStockInDB(
                $invoice,
                $detail,
                $quantityInDBTableFrom,
                $invoice['from_warehouse_id']
            );

            $stockTo = self::updateStockInDB(
                $invoice,
                $detail,
                $quantityInDBTableTo,
                $invoice['to_warehouse_id']
            );

            if ($detail['product_type'] === ProductTypesEnum::CONSUMER_ITEM) {
                $quantityPatchInDBTableFrom = self::qtePatchInDB(
                    $invoice['from_warehouse_id'],
                    $detail
                );

                $quantityPatchInDBTableTo = self::qtePatchInDB(
                    $invoice['to_warehouse_id'],
                    $detail
                );

                $quantityPatchInDBTableFrom -= self::stockyByUnit($detail['unit_id'], $detail['quantity']);
                $quantityPatchInDBTableTo += self::stockyByUnit($detail['unit_id'], $detail['quantity']);

                $patchFrom = self::updateOrCreatePatchInDB(
                    $invoice,
                    $detail,
                    $quantityPatchInDBTableFrom,
                    $stockFrom,
                    $invoice['from_warehouse_id']
                );

                $patchTo = self::updateOrCreatePatchInDB(
                    $invoice,
                    $detail,
                    $quantityPatchInDBTableTo,
                    $stockTo,
                    $invoice['to_warehouse_id']
                );

                $detail->update(['patch_id' => $patchTo['id']]);
            }
        }
    }

    public static function calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable, $action)
    {
        if ($action === self::FROM) {
            return SaleService::calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable);
        }

        if ($action === self::TO) {
            return PurchaseService::calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable);
        }
    }

    public static function updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete)
    {
        // From
        $stockFrom = self::updateStockInDB(
            $invoice,
            $detail,
            self::calcUpdatedQte(
                $invoice,
                $detail,
                $oldDetail,
                $isComplete,
                self::qteStockInDB(
                    $invoice['from_warehouse_id'],
                    $detail
                ),
                self::FROM
            ),
            $invoice['from_warehouse_id']
        );

        if ($oldDetail['product_type'] === ProductTypesEnum::CONSUMER_ITEM) {
            self::updateOrCreatePatchInDB(
                $invoice,
                $detail,
                self::calcUpdatedQte(
                    $invoice,
                    $detail,
                    $oldDetail,
                    $isComplete,
                    self::qtePatchInDB(
                        $invoice['from_warehouse_id'],
                        $detail
                    ),
                    self::FROM
                ),
                $stockFrom,
                $invoice['from_warehouse_id']
            );
        }

        // To
        $stockTo = self::updateStockInDB(
            $invoice,
            $detail,
            self::calcUpdatedQte(
                $invoice,
                $detail,
                $oldDetail,
                $isComplete,
                self::qteStockInDB(
                    $invoice['to_warehouse_id'],
                    $detail
                ),
                self::TO
            ),
            $invoice['to_warehouse_id']
        );

        if ($oldDetail['product_type'] === ProductTypesEnum::CONSUMER_ITEM) {
            self::updateOrCreatePatchInDB(
                $invoice,
                $detail,
                self::calcUpdatedQte(
                    $invoice,
                    $detail,
                    $oldDetail,
                    $isComplete,
                    self::qtePatchInDB(
                        $invoice['to_warehouse_id'],
                        $detail
                    ),
                    self::TO
                ),
                $stockTo,
                $invoice['to_warehouse_id']
            );
        }
    }

    public static function destroyDetails($invoice, $deletedDetails)
    {
        $old_isComplete = self::isComplete($invoice->stage_id);
        $deletedIds = [];
        if (count($deletedDetails)) {
            foreach ($deletedDetails as $deletedDetail) {
                if (isset($deletedDetail['id'])) {
                    $deletedIds[] = $deletedDetail['id'];

                    if ($old_isComplete) {
                        // From
                        $quantityFrom = self::qteStockInDB(
                            $invoice['from_warehouse_id'],
                            $deletedDetail
                        ) + self::stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                        $stockFrom = self::updateStockInDB($invoice, $deletedDetail, $quantityFrom, $invoice['from_warehouse_id']);

                        if ($deletedDetail['product_type'] === ProductTypesEnum::CONSUMER_ITEM->value) {
                            $quantityInPatchFrom = self::qtePatchInDB(
                                $invoice['from_warehouse_id'],
                                $deletedDetail
                            ) + self::stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                            self::updateOrCreatePatchInDB($invoice, $deletedDetail, $quantityInPatchFrom, $stockFrom, $invoice['from_warehouse_id']);
                        }

                        // To
                        $quantityTo = self::qteStockInDB(
                            $invoice['from_warehouse_id'],
                            $deletedDetail
                        ) - self::stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                        $stockTo = self::updateStockInDB($invoice, $deletedDetail, $quantityTo, $invoice['to_warehouse_id']);

                        if ($deletedDetail['product_type'] === ProductTypesEnum::CONSUMER_ITEM->value) {
                            $quantityInPatchTo = self::qtePatchInDB(
                                $invoice['to_warehouse_id'],
                                $deletedDetail
                            ) - self::stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                            self::updateOrCreatePatchInDB($invoice, $deletedDetail, $quantityInPatchTo, $stockTo, $invoice['to_warehouse_id']);
                        }
                    }
                }
            }
            $invoice->details()->whereIn('id', $deletedIds)->delete();
        }
    }
}
