<?php

namespace App\Traits;

use App\Enums\InvoiceTypesEnum;
use App\Enums\ProductTypesEnum;
use Illuminate\Support\Arr;
use Modules\Detail\Models\Detail;
use Modules\Patch\Models\Patch;
use Modules\Item\Models\Item;
use Modules\Variant\Models\Variant;
use Modules\Stage\Models\Stage;
use Modules\Stock\Models\Stock;
use Modules\Unit\Models\Unit;

trait InvoiceTrait
{
  const STAGE_COMPLETE = 100;

  public function updateInvoice($invoice, $request)
  {
    $isComplete = $this->isComplete($request['pipeline_id'], $request['stage_id']);
    $invoice->update($request + ['effected' => $isComplete]);
    return $invoice;
  }

  public function destroyInvoice($invoice)
  {
    $invoice->delete();
    return $invoice;
  }

  public function createDetails($invoice, $details)
  {
    if (count($details)) {
      return $invoice->details()->createMany($details);
    }
  }

  public function updateDetails($invoice, $requestDetails)
  {
    $oldDetailsIds = $this->oldDetailsIds($requestDetails, $invoice->details);

    if (count($oldDetailsIds)) {
      Detail::upsert($this->updateDetailsArray($requestDetails, $invoice->details), ['id']);
    }
  }

  public function oldDetailsIds($requestDetails, $oldDetails)
  {
    $invoice_detailsIds = [];
    foreach ($requestDetails as $detail) {
      if (isset($detail['id'])) {
        foreach ($oldDetails as $oldDetail) {
          if ($oldDetail['id'] === $detail['id']) {
            $invoice_detailsIds[] = $detail['id'];
          }
        }
      }
    }

    return $invoice_detailsIds;
  }

  public function updateDetailsArray($requestDetails, $oldDetails)
  {
    $update_invoice_details = [];
    foreach ($requestDetails as $detail) {
      if (isset($detail['id'])) {
        foreach ($oldDetails as $oldDetail) {
          if ($oldDetail['id'] === $detail['id']) {
            $update_invoice_details[] = $this->detailArray($detail);
          }
        }
      }
    }
    return $update_invoice_details;
  }

  public function detailArray($detail)
  {
    return [
      'id' => $detail['id'],
      'patch_id' => $detail['patch_id'],
      'amount' => $detail['amount'],
      'tax' => $detail['tax'],
      'tax_type' => $detail['tax_type'],
      'discount' => $detail['discount'],
      'discount_type' => $detail['discount_type'],
      'unit_id' => $detail['unit_id'],
      'detailable_id' => $detail['detailable_id'],
      'detailable_type' => $detail['detailable_type'],
      'warehouse_id' => $detail['warehouse_id'],
      'item_id' => $detail['item_id'],
      'variant_id' => $detail['variant_id'],
      'total' => $detail['total'],
      'quantity' => $detail['quantity'],
      'product_type' => $detail['product_type'],
      'type' => $detail['type'],
      'production_date' => $detail['production_date'],
      'expired_date' => $detail['expired_date'],
      'movement' => self::INV_TYPE === InvoiceTypesEnum::ADJUSTMENT->value ? $detail['movement'] : null,
    ];
  }

  public function updateStockInCreate($invoice, $details, $isComplete)
  {
    collect($details)
      ->each(function ($detail) use ($invoice, $isComplete) {
        $stock = $this->updateStockInDB(
          $invoice,
          $detail,
          $this->calcQte($detail, $isComplete, self::qteStockInDB($invoice['warehouse_id'], $detail))
        );

        if ($detail['product_type'] === ProductTypesEnum::CONSUMER_ITEM) {
          $patch = $this->updateOrCreatePatchInDB(
            $invoice,
            $detail,
            $this->calcQte($detail, $isComplete, self::qtePatchInDB($invoice['warehouse_id'], $detail)),
            $stock
          );


          $detail->update(['patch_id' => $patch['id']]);
        }
      });
  }

  public function createNewDetailsAndUpdateStockInUpdate($requestDetails, $invoice, $params)
  {
    $isComplete = $this->isComplete($params['pipeline_id'], $params['stage_id']);
    $new_invoice_details = collect($requestDetails)
      ->whereNull('id')->all();
    if (count($new_invoice_details)) {
      $createdDetails = $this->createDetails($invoice, $new_invoice_details);
      $createdDetails->each(function ($detail) use ($invoice, $isComplete) {
        $stock = $this->updateStockInDB(
          $invoice,
          $detail,
          $this->calcQte($detail, $isComplete, self::qteStockInDB($invoice['warehouse_id'], $detail))
        );

        if ($detail['product_type'] === ProductTypesEnum::CONSUMER_ITEM) {
          $patch = $this->updateOrCreatePatchInDB(
            $invoice,
            $detail,
            $this->calcQte($detail, $isComplete, self::qtePatchInDB($invoice['warehouse_id'], $detail)),
            $stock
          );

          $detail->update(['patch_id' => $patch['id']]);
        }
        return $detail;
      });
    }
  }

  public function updateStockForOldDetails($invoice, $details, $params, $old_isComplete, $invoice_effected)
  {
    $isComplete = $this->isComplete($params['pipeline_id'], $params['stage_id']);
    $oldDetails = $invoice->details;
    $requestDetails = $details;
    $oldInvoiceEffected = $invoice_effected;
    
    foreach ($requestDetails as $detail) {
      if (isset($detail['id'])) {
        foreach ($oldDetails as $oldDetail) {
          if ($oldDetail['id'] === $detail['id']) {
            $stock = $this->updateStockInDB(
              $invoice,
              $detail,
              $this->calcUpdatedQte(
                $oldInvoiceEffected,
                $detail,
                $oldDetail,
                $isComplete,
                $old_isComplete,
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
                  $oldInvoiceEffected,
                  $detail,
                  $oldDetail,
                  $isComplete,
                  $old_isComplete,
                  self::qtePatchInDB(
                    $invoice['warehouse_id'],
                    $detail
                  )
                ),
                $stock
              );
            }
          }
        }
      }
    }
  }

  public function updateStockInDB($invoice, $detail, $quantity)
  {
    $stock = self::findStockInDB($invoice['warehouse_id'], $detail);
    if ($stock && self::INV_TYPE !== InvoiceTypesEnum::QUOTATION->value) {
      $stock->update([
        'quantity' => $quantity
      ]);
    }

    if (!$stock) {
      $isComplete = $this->isComplete($invoice['pipeline_id'], $invoice['stage_id']);
      $quantity = self::INV_TYPE === InvoiceTypesEnum::QUOTATION->value ? 0 : $this->calcQte($detail, $isComplete, self::qteStockInDB($invoice['warehouse_id'], $detail));

      Stock::create([
        'item_id' => $detail['item_id'],
        'variant_id' => $detail['variant_id'],
        'quantity' => $quantity,
        'warehouse_id' => $invoice['warehouse_id'],
      ]);
    }

    return $stock;
  }

  public function updateOrCreatePatchInDB($invoice, $detail, $quantity, $stock)
  {
    // Find A Patch If Exist Update It
    $patch = self::findPatchInDB($invoice['warehouse_id'], $detail);
    
    if ($patch && self::INV_TYPE !== InvoiceTypesEnum::QUOTATION->value) {
      $patch->update([
        'quantity' => $quantity
      ]);
    }
    // Find A Patch If Not Exist Create It
    if (!$patch) {
      $isComplete = $this->isComplete($invoice['pipeline_id'], $invoice['stage_id']);
      $quantity = self::INV_TYPE === InvoiceTypesEnum::QUOTATION->value ? 0 : $this->calcQte($detail, $isComplete, self::qtePatchInDB($invoice['warehouse_id'], $detail));

      $patch = Patch::create([
        'stock_id' => $stock['id'],
        'item_id' => $detail['item_id'],
        'variant_id' => $detail['variant_id'],
        'production_date' => $detail['production_date'],
        'expired_date' => $detail['expired_date'],
        'unit_id' => $detail['unit_id'],
        'amount' => in_array(self::INV_TYPE, ['purchase', 'purchase_return']) ? $detail['amount'] : ($detail['variant_id'] ? Variant::where('id', $detail['variant_id'])->first()->cost : Item::where('id', $detail['item_id'])->first()->cost),
        'quantity' => $quantity,
        'warehouse_id' => $invoice['warehouse_id'],
      ]);
    }

    return $patch;
  }

  public function createPayments($invoice, $requestPayments)
  {
    if (count($requestPayments)) {
      $new_payments = [];
      foreach ($requestPayments as $payment) {
        if (!isset($payment['id'])) {
          $new_payments[] = [
            'date' => $payment['date'],
            'type' => $payment['type'],
            'amount' => $payment['amount'],
            'received_amount' => $payment['received_amount'],
            'note' => $payment['note']
          ];
        }
      }
      $this->createPaymentsInDB($invoice, $new_payments);
    }
  }

  public function createPaymentsInDB($invoice, $new_payments)
  {
    if ($new_payments) $invoice->payments()->createMany($new_payments);
  }

  public function destroyPayments($invoice, $deletedPayments)
  {
    $deletedIds = [];

    if (count($deletedPayments)) {
      foreach ($deletedPayments as $deletedPayment) {
        if (isset($deletedPayment['id'])) {
          $deletedIds[] = $deletedPayment['id'];
        }
      }
      $invoice->payments()->whereIn('id', $deletedIds)->delete();
    }
  }

  public function stockyByUnit($unitId, $stocky)
  {
    $claculateStocky = 0;
    $unit = Unit::whereId($unitId)->first();
    if($unit) {
      if ($unit->operator === '/') {
        $claculateStocky = $stocky / $unit->operator_value;
      } else {
        $claculateStocky = $stocky * $unit->operator_value;
      }
    }
    return $claculateStocky;
  }

  public static function qteStockInDB($warehouse, $detail)
  {
    $stock = self::findStockInDB($warehouse, $detail);

    if ($stock) return $stock->quantity;

    return 0;
  }

  public static function qtePatchInDB($warehouse, $detail)
  {
    $patch = self::findPatchInDB($warehouse, $detail);

    if ($patch) return $patch->quantity;

    return 0;
  }

  public static function findStockInDB($warehouse, $detail)
  {
    return Stock::query()
      ->where('warehouse_id', $warehouse)
      ->where('item_id', $detail['item_id'])
      ->where('variant_id', $detail['variant_id'])
      ->first();
  }

  public static function findPatchInDB($warehouse, $detail)
  {
    if ($detail['patch_id']) {
      return Patch::find($detail['patch_id']);
    }
    return Patch::query()
      ->where('warehouse_id', $warehouse)
      ->where('item_id', $detail['item_id'])
      ->where('variant_id', $detail['variant_id'])
      ->where('amount', in_array(self::INV_TYPE, ['purchase', 'purchase_return']) ? $detail['amount'] : ($detail['variant_id'] ? Variant::where('id', $detail['variant_id'])->first()->cost : Item::where('id', $detail['item_id'])->first()->cost))
      ->when(
        !empty($detail['production_date']),
        fn ($query) => $query->whereDate('production_date', $detail['production_date'])
      )
      ->when(
        !empty($detail['expired_date']),
        fn ($query) => $query->whereDate('expired_date', $detail['expired_date'])
      )
      ->first();
  }

  public function isComplete($pipelineId, $stageId)
  {
    $stage = Stage::whereId($stageId)->wherePipelineId($pipelineId)->first();
    if ($stage) {
      return $stage->complete === self::STAGE_COMPLETE;
    }
    // Invoice will be effected if not find pipeline or not choose any pipeline & stage
    return true;
  }

  public function isDuplicateDetails($details)
  {
    return collect($details)
      ->map(fn (array $detail) => Arr::only($detail, ['item_id', 'variant_id', 'amount', 'production_date', 'expired_date']))
      ->duplicates()
      ->isNotEmpty();
  }
}
