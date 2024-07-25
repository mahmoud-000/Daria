<?php

namespace Modules\SaleReturn\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Enums\ProductTypesEnum;
use App\Traits\InvoiceTrait;

class SaleReturnService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::SALE_RETURN->value;

    public function calcQte($invoice, $detail, $isComplete)
    {
        if ($isComplete) {
            $quantityInDBTable = self::qteStockInDB($invoice['warehouse_id'], $detail);

            $quantityInDBTable += $this->stockyByUnit($detail['unit_id'], $detail['quantity']);

            $stock = $this->updateStockInDB(
                $invoice,
                $detail,
                $quantityInDBTable
            );

            if ($detail['product_type'] === ProductTypesEnum::CONSUMER_ITEM) {
                $quantityPatchInDBTable = self::qtePatchInDB(
                    $invoice['warehouse_id'],
                    $detail
                );

                $quantityInDBTable += $this->stockyByUnit($detail['unit_id'], $detail['quantity']);

                $patch = $this->updateOrCreatePatchInDB(
                    $invoice,
                    $detail,
                    $quantityPatchInDBTable,
                    $stock
                );


                $detail->update(['patch_id' => $patch['id']]);
            }
        }
    }

    public function calcUpdatedQte($invoice, $detail, $oldDetail, $isComplete, $quantityInDBTable)
    {
        $old_isComplete = $this->isComplete($invoice->stage_id);

        $qte = $quantityInDBTable;

        if ($invoice->effected) {
            if ($isComplete) {
                if ($oldDetail['quantity'] == $detail['quantity']) {
                    $qte = $quantityInDBTable;
                } else {
                    $sum = $oldDetail['quantity'] - $detail['quantity'];
                    $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $sum);
                }
            } else {
                $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $oldDetail['quantity']);
            }
        } else {
            if ($old_isComplete) {
                $qte = $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
            } else {
                if (!$old_isComplete && $isComplete) {
                    $qte = $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
                }

                if ($old_isComplete && !$isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $sum);
                    }
                }

                if ($old_isComplete && $isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $quantityInDBTable;
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $sum);
                    }
                }

                if ((!$old_isComplete && !$isComplete)) {
                    $qte = $quantityInDBTable;
                }
            }
        }
        return $qte;
    }

    public function updateStockAndPatch($invoice, $detail, $oldDetail, $isComplete)
    {
        $stock = $this->updateStockInDB(
            $invoice,
            $detail,
            $this->calcUpdatedQte(
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
            $this->updateOrCreatePatchInDB(
                $invoice,
                $detail,
                $this->calcUpdatedQte(
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

    public function destroyDetails($invoice, $deletedDetails)
    {
        $old_isComplete = $this->isComplete($invoice->stage_id);
        $deletedIds = [];

        if (count($deletedDetails)) {
            foreach ($deletedDetails as $deletedDetail) {
                if (isset($deletedDetail['id'])) {
                    $deletedIds[] = $deletedDetail['id'];

                    if ($old_isComplete) {
                        $quantity = self::qteStockInDB(
                            $invoice['warehouse_id'],
                            $deletedDetail
                        ) - $this->stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                        $stock = $this->updateStockInDB($invoice, $deletedDetail, $quantity);

                        if ($deletedDetail['product_type'] === ProductTypesEnum::CONSUMER_ITEM->value) {
                            $quantityInPatch = self::qtePatchInDB(
                                $invoice['warehouse_id'],
                                $deletedDetail
                            ) - $this->stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                            $this->updateOrCreatePatchInDB($invoice, $deletedDetail, $quantityInPatch, $stock);
                        }
                    }
                }
            }
            $invoice->details()->whereIn('id', $deletedIds)->delete();
        }
    }
}
