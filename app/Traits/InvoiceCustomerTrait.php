<?php

namespace App\Traits;

use Modules\Customer\Models\Customer;

trait InvoiceCustomerTrait
{
  public function customer()
  {
    return $this->belongsTo(Customer::class)->withTrashed();
  }
}
