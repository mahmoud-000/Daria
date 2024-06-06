<?php

namespace Modules\PurchaseReturn\Http\Services;

use App\Interface\Defination;
use App\Traits\InvoiceTrait;

class PurchaseReturnService implements Defination
{
    use InvoiceTrait;

    public function calculateStocky($detail, $isComplete)
    {
        $stocky = 0;

        if ($isComplete) {
            $stocky = $detail['stocky'] - $detail['quantity'];
        } else {
            $stocky = $detail['stocky'];
        }

        $claculateStocky = $this->stockyWithUnit($detail['unit_id'], $stocky);
        return $claculateStocky;
    }

    public function claculateUpdateStocky($oldInvoiceEffected, $detail, $oldDetail, $isComplete, $old_isComplete)
    {

        $stocky = $detail['stocky'];

        if ($oldInvoiceEffected === self::INVOICE_EFFECTED) {
            if ($isComplete) {
                if ($oldDetail['quantity'] == $detail['quantity']) {
                    $stocky = $detail['stocky'];
                } else {
                    $sum = $oldDetail['quantity'] - $detail['quantity'];
                    $stocky = $detail['stocky'] + $sum;
                }
            } else {
                if ($oldDetail['quantity'] == $detail['quantity']) {
                    $stocky = $detail['stocky'] + $detail['quantity'];
                } else {
                    $sum = $oldDetail['quantits'] - $detail['quantity'];
                    $stocky = $detail['stocky'] + $sum;
                }
            }
        } else {
            if ($old_isComplete) {
                $stocky = $detail['stocky'] - $detail['quantity'];
            } else {

                if (!$old_isComplete && $isComplete) {
                    $stocky = $detail['stocky'] - $detail['quantity'];
                }

                if ($old_isComplete && !$isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $stocky = $detail['stocky'] + $detail['quantity'];
                    } else {
                        $sum = $oldDetail['quantits'] - $detail['quantity'];
                        $stocky = $detail['stocky'] + $sum;
                    }
                }

                if ($old_isComplete && $isComplete) {
                    if ($oldDetail['quantity'] == $detail['quantity']) {
                        $stocky = $detail['stocky'];
                    } else {
                        $sum = $oldDetail['quantits'] - $detail['quantity'];
                        $stocky = $detail['stocky'] + $sum;
                    }
                }

                if ((!$old_isComplete && !$isComplete)) {
                    $stocky = $detail['stocky'];
                }
            }
        }

        return  $this->stockyWithUnit($detail['unit_id'], $stocky);
    }

    public function destroyDetails($invoice, $deletedDetails, $old_isComplete)
    {
        $deletedIds = [];
        $update_item_warehouse = [];
        $updatedIds = [];
        $detail = [];

        if (count($deletedDetails)) {
            foreach ($deletedDetails as $deletedDetail) {
                if (isset($deletedDetail['id'])) {
                    $deletedIds[] = $deletedDetail['id'];
                    if ($old_isComplete) {
                        $updatedIds[] = self::stockDB($deletedDetail['item_id'], $deletedDetail['item_variant_id'], $invoice->warehouse_id);
                        $stocky = $deletedDetail['stocky'] + $deletedDetail['quantity'];
                        $detail = $deletedDetail;
                        $detail['stocky'] = $stocky;
                        $update_item_warehouse[] = $detail;
                    }
                }
            }
            $this->updateWarehouseInDB($invoice, $update_item_warehouse);
            $invoice->details()->whereIn('id', $deletedIds)->delete();
        }
    }
}
