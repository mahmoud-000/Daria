<?php

namespace Modules\Sale\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Enums\ProductTypesEnum;
use App\Traits\InvoiceTrait;

class SaleService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::SALE->value;
    
    public function calcQte($detail, $isComplete, $quantityInDBTable)
    {
        if ($isComplete) {
            return $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
        }

        return $quantityInDBTable;
    }

    public function calcUpdatedQte($oldSaleEffected, $detail, $oldDetail, $isComplete, $old_isComplete, $quantityInDBTable)
    {
        $qte = $quantityInDBTable;
        if ($oldSaleEffected) {
            if ($isComplete) {
                if ($oldDetail['quantity'] == $detail['quantity']) {
                    $qte = $quantityInDBTable;
                } else {
                    $sum = $oldDetail['quantity'] - $detail['quantity'];
                    $qte = $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $sum);
                }
            } else {
                $qte = $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $oldDetail['quantity']);
            }
        } else {
            if ($old_isComplete) {
                $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
            } else {
                if (!$old_isComplete && $isComplete) {
                    $qte = $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
                }

                if ($old_isComplete && !$isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $sum);
                    }
                }

                if ($old_isComplete && $isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $quantityInDBTable;
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $sum);
                    }
                }

                if ((!$old_isComplete && !$isComplete)) {
                    $qte = $quantityInDBTable;
                }
            }
        }
        return $qte;
    }

    public function destroyDetails($invoice, $deletedDetails, $old_isComplete)
    {
        $deletedIds = [];

        if (count($deletedDetails)) {
            foreach ($deletedDetails as $deletedDetail) {
                if (isset($deletedDetail['id'])) {
                    $deletedIds[] = $deletedDetail['id'];

                    if ($old_isComplete) {
                        $quantity = self::qteStockInDB(
                            $invoice,
                            $deletedDetail
                        ) + $this->stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                        $stock = $this->updateStockInDB($invoice, $deletedDetail, $quantity);

                        if ($deletedDetail['product_type'] === ProductTypesEnum::CONSUMER_ITEM->value) {
                            $quantityInPatch = self::qtePatchInDB(
                                $invoice,
                                $deletedDetail
                            ) + $this->stockyByUnit($deletedDetail['unit_id'], $invoice->details->where('id', $deletedDetail['id'])->first()->quantity);

                            $this->updateOrCreatePatchInDB($invoice, $deletedDetail, $quantityInPatch, $stock);
                        }
                    }
                }
            }
            $invoice->details()->whereIn('id', $deletedIds)->delete();
        }
    }
}
