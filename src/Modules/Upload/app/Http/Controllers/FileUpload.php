<?php

namespace Modules\Upload\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Upload\Models\TempFiles;

class FileUpload extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            // md5
            $filename = date('Y_m_d') . '_' . pathinfo(str($file->getClientOriginalName())->lower()->slug('_'), PATHINFO_FILENAME) . '_' . time() . '.' . $ext;

            $folder = $request->folder;
            $path = 'temp/' . $folder;

            $url = $file->storeAs($path, $filename, ['disk' => 'public']);

            TempFiles::create([
                'folder'    => $folder,
                'filename'  => $filename,
                'ext'       => $ext
            ]);
            return $this->success(['filename' => $filename, 'url' => url('storage/' . $url)]);
        }
    }
}
