<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HideFieldsTrait
{
  protected $withoutFields = [];

  public static function collection($resource)
  {
    return tap(new (__CLASS__ . 'Collection')($resource), function ($collection) {
      $collection->collects = __CLASS__;
    });
  }

  public function hide(array $fields)
  {
    $this->withoutFields = $fields;
    return $this;
  }
  
  protected function filterFields($array)
  {
    return collect($array)->forget($this->withoutFields)->toArray();
  }

  protected function processCollection($request)
  {
    $class = Str::remove('Collection', __CLASS__);

    return $this->collection->map(function ($resource) use ($request, $class) {
      return $class::make($resource)->hide($this->withoutFields)->toArray($request);
    })->all();
  }
}