<?php

namespace App\Traits;

use Modules\Detail\Models\Detail;
use Modules\Payment\Models\Payment;
use Modules\Pipeline\Models\Pipeline;
use Modules\Stage\Models\Stage;
use Modules\Warehouse\Models\Warehouse;

trait BaseInvoiceRelationsTrait
{
  public function warehouse()
  {
    return $this->belongsTo(Warehouse::class)->withTrashed();
  }

  public function details()
  {
    return $this->morphMany(Detail::class, 'detailable');
  }

  public function payments()
  {
    return $this->morphMany(Payment::class, 'paymentable');
  }

  public function pipeline()
  {
    return $this->belongsTo(Pipeline::class)->withTrashed();
  }

  public function stage()
  {
    return $this->belongsTo(Stage::class)->withTrashed();
  }
}
