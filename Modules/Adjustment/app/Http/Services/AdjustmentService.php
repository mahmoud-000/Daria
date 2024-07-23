<?php

namespace Modules\Adjustment\Http\Services;

use App\Enums\InvoiceTypesEnum;
use App\Enums\ProductTypesEnum;
use App\Traits\InvoiceTrait;

class AdjustmentService
{
    use InvoiceTrait;

    const INV_TYPE = InvoiceTypesEnum::ADJUSTMENT->value;
    const ADDITION = 1;
    const SUBSTRACTION = 2;

    public function calcQte($detail, $isComplete, $quantityInDBTable)
    {
        if ($isComplete) {
            if ($detail['movement'] === self::ADDITION) {
                return $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
            }

            if ($detail['movement'] === self::SUBSTRACTION) {
                return $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
            }
        }

        return $quantityInDBTable;
    }

    public function calcUpdatedQte($oldAdjustmentEffected, $detail, $oldDetail, $isComplete, $old_isComplete, $quantityInDBTable)
    {
        $qte = $quantityInDBTable;
        if ($oldAdjustmentEffected) {
            if ($isComplete) {
                if ($oldDetail['quantity'] == $detail['quantity']) {
                    $qte = $quantityInDBTable;
                } else {
                    $sum = $oldDetail['quantity'] - $detail['quantity'];
                    $qte = $detail['movement'] === self::ADDITION ? $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $sum) : $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $sum);
                }
            } else {
                $qte = $detail['movement'] === self::ADDITION ? $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $oldDetail['quantity']) : $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $oldDetail['quantity']);
            }
        } else {
            if ($old_isComplete) {
                $qte = $detail['movement'] === self::ADDITION ? $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']) : $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
            } else {
                if (!$old_isComplete && $isComplete) {
                    $qte = $detail['movement'] === self::ADDITION ? $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']) : $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
                }

                if ($old_isComplete && !$isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $detail['movement'] === self::ADDITION ? $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $detail['quantity']) : $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $detail['quantity']);
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $detail['movement'] === self::ADDITION ? $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $sum) : $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $sum);
                    }
                }

                if ($old_isComplete && $isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $qte = $quantityInDBTable;
                    } else {
                        $sum = $oldDetail['quantity'] - $detail['quantity'];
                        $qte = $detail['movement'] === self::ADDITION ? $quantityInDBTable - $this->stockyByUnit($detail['unit_id'], $sum) : $quantityInDBTable + $this->stockyByUnit($detail['unit_id'], $sum);
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
                        $detail = $invoice->details->where('id', $deletedDetail['id'])->first();

                        $quantity = $detail['movement'] === self::ADDITION
                            ? self::qteStockInDB(
                                $invoice,
                                $deletedDetail
                            ) - $this->stockyByUnit($deletedDetail['unit_id'], $detail->quantity)
                            : self::qteStockInDB(
                                $invoice,
                                $deletedDetail
                            ) + $this->stockyByUnit($deletedDetail['unit_id'], $detail->quantity);

                        $stock = $this->updateStockInDB($invoice, $deletedDetail, $quantity);

                        if ($deletedDetail['product_type'] === ProductTypesEnum::CONSUMER_ITEM->value) {
                            $quantityInPatch = $detail['movement'] === self::ADDITION
                                ? self::qtePatchInDB(
                                    $invoice,
                                    $deletedDetail
                                ) - $this->stockyByUnit($deletedDetail['unit_id'], $detail->quantity)
                                : self::qtePatchInDB(
                                    $invoice,
                                    $deletedDetail
                                ) + $this->stockyByUnit($deletedDetail['unit_id'], $detail->quantity);

                            $this->updateOrCreatePatchInDB($invoice, $deletedDetail, $quantityInPatch, $stock);
                        }
                    }
                }
            }
            $invoice->details()->whereIn('id', $deletedIds)->delete();
        }
    }
}
