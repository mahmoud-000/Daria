<?php

namespace Modules\Upload\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Upload\Models\TempFiles;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileDestroy extends Controller
{
  public function __invoke(Request $request)
  {
    DB::transaction(function () use ($request) {
      if ($request->id) {
        $media = Media::where('uuid', $request->id)->first();
        $media->delete();

        $path = $media->disk . '/' . $media->id;
        $checkFile = Storage::exists($path);

        if ($checkFile) {
          Storage::deleteDirectory($path);
        }
      } else {
        $path = 'public/temp/' . $request->collection . '/' . $request->filename;
        $checkFile = Storage::exists($path);

        if ($checkFile) {
          Storage::delete($path);
        }

        // This Code To Fix System Logo or Prodile
        // Or any Form Updates in Same Page and not redirect
        $conversion = $request->collection;
        $media = Media::where('file_name', $request->filename)
          ->where("generated_conversions->{$conversion}", true)
          ->first();
        $media->delete();

        $path = $media->disk . '/' . $media->id;
        $checkFile = Storage::exists($path);

        if ($checkFile) {
          Storage::deleteDirectory($path);
        }
      }
      TempFiles::where('folder', $request->collection)->where('filename', $request->filename)->delete();
    });
  }
}