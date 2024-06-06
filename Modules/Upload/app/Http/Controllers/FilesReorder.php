<?php

namespace Modules\Upload\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FilesReorder extends Controller
{
  public function __invoke(Request $request)
  {
    foreach ($request->all() as $image) {
      Media::where('uuid', $image['uuid'])->update(['order_column' => $image['order_column']]); 
    }

    return $this->success();
  }
}
