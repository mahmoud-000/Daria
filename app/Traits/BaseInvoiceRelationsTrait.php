<?php

namespace App\Traits;

use Modules\Detail\Models\Detail;
use Modules\Pipeline\Models\Pipeline;
use Modules\Stage\Models\Stage;
use Modules\User\Models\User;
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

  public function pipeline()
  {
    return $this->belongsTo(Pipeline::class)->withTrashed();
  }

  public function stage()
  {
    return $this->belongsTo(Stage::class)->withTrashed();
  }

  public function user()
  {
    return $this->belongsTo(User::class)->withTrashed();
  }
}
