<?php

namespace App\Traits;

use Modules\Supplier\Models\Supplier;

trait InvoiceSupplierTrait
{
  public function supplier()
  {
    return $this->belongsTo(Supplier::class)->withTrashed();
  }
}
