<?php

namespace Modules\Purchase\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Enums\ProductTypesEnum;
use App\Traits\InvoiceTrait;

class PurchaseService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::PURCHASE->value;

    public static function calcQte($invoice, $detail, $isComplete)
    {
        if ($isComplete) {
            $quantityInDBTable = self::qteStockInDB($invoice['warehouse_id'], $detail);

            $quantityInDBTable += self::stockyByUnit($detail['unit_id'], $detail['quantity']);

            $stock = self::updateStockInDB(
                $invoice,
                $detail,
                $quantityInDBTable
            );

            $detail->update(['stock_id' => $stock['id']]);

            if ($detail['product_type'] === ProductTypesEnum::CONSUMER_ITEM) {
                $quantityPatchInDBTable = self::qtePatchInDB(
                    $invoice['warehouse_id'],
                    $detail
                );

                $quantityPatchInDBTable += self::stockyByUnit($detail['unit_id'], $detail['quantity']);

                $patch = self::updateOrCreatePatchInDB(
                    $invoice,
                    $detail,
                    $quantityPatchInDBTable,
                    $stock
                );

                $detail->update(['patch_id' => $patch['id']]);
            }
        }
    }

    public static function calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable)
    {
        $old_isComplete = self::isComplete($invoice->stage_id);

        $qte = $quantityInDBTable;

        if ($invoice->effected) {
            if ($isComplete) {
                if ($oldDetail['quantity'] == $detail['quantity']) {
                    $qte = $quantityInDBTable;
                } else {
                    $sum = $oldDetail['quantity'] - $detail['quantity'];
                    $qte = $quantityInDBTable - self::stockyByUnit($detail['unit_id'], $sum);
                }
            } else {
                $qte = $quantityInDBTable - self::stockyByUnit($detail['unit_id'], $oldDetail['quantity']);
            }
        } else {
            if ($old_isComplete) {
                $qte = $quantityInDBTable + self::stockyByUnit($detail['unit_id'], $detail['quantity']);
            } else {
                if (!$old_isComplete && $isComplete) {
                    $qte = $quantityInDBTable + self::stockyByUnit($detail['unit_id'], $detail['quantity']);
                }

                if ($old_isComplete && !$isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $quantityInDBTable - self::stockyByUnit($detail['unit_id'], $detail['quantity']);
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $quantityInDBTable - self::stockyByUnit($detail['unit_id'], $sum);
                    }
                }

                if ($old_isComplete && $isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $quantityInDBTable;
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $quantityInDBTable - self::stockyByUnit($detail['unit_id'], $sum);
                    }
                }

                if ((!$old_isComplete && !$isComplete)) {
                    $qte = $quantityInDBTable;
                }
            }
        }

        return $qte;
    }

    public static function updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete)
    {
        $stock = self::updateStockInDB(
            $invoice,
            $detail,
            self::calcUpdatedQte(
                $invoice,
                $detail,
                $oldDetail,
                $isComplete,
                self::qteStockInDB(
                    $invoice['warehouse_id'],
                    $detail
                )
            )
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
                        $invoice['warehouse_id'],
                        $detail
                    )
                ),
                $stock
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
                        $quantity = self::qteStockInDB(
                            $invoice['warehouse_id'],
                            $deletedDetail
                        ) - self::stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                        $stock = self::updateStockInDB($invoice, $deletedDetail, $quantity);

                        if ($deletedDetail['product_type'] === ProductTypesEnum::CONSUMER_ITEM->value) {
                            $quantityInPatch = self::qtePatchInDB(
                                $invoice['warehouse_id'],
                                $deletedDetail
                            ) - self::stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                            self::updateOrCreatePatchInDB($invoice, $deletedDetail, $quantityInPatch, $stock);
                        }
                    }
                }
            }
            $invoice->details()->whereIn('id', $deletedIds)->delete();
        }
    }
}
