<?php

namespace Modules\Upload\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Upload\Models\TempFiles;

class FilesAssign extends Controller
{
    public function __invoke($files, $module, $collection = null, $folderTemp = null, $multiple = false)
    {
        DB::transaction(function () use ($files, $module, $collection, $folderTemp, $multiple) {
            if (!$multiple) {
                if (isset($files['filename'])) {
                    self::handleAddMedia($files['filename'], $module, $collection, $folderTemp);
                }
            } else {
                $isArrayOfArrays = empty(array_filter($files, function ($item) {
                    return !is_array($item);
                }));

                if ($isArrayOfArrays) {
                    foreach ($files as $file) {
                        if (!array_key_exists('fake', $file)) {
                            self::handleAddMedia($file['filename'], $module, $collection, $folderTemp);
                        }
                    }
                }
            }
        });
    }

    protected static function handleAddMedia($file, $module, $collection, $folderTemp)
    {
        $temp = TempFiles::where('filename', $file)->where('folder', $folderTemp)->first();
        if ($temp) {
            // $getExt = self::getFileType($temp->ext);
            $module->addMedia(storage_path('app/public/temp/' . $folderTemp . '/' . $file))->toMediaCollection($collection);
            $temp->delete();
        }
    }

    public static function getFileType($ext): string
    {
        $supported_image = [
            'png',
            'jpeg',
        ];
        $supported_archive = [
            'zip',
            'rar'
        ];
        $supported_doc = [
            'pdf',
            'doc',
            'docx',
            'ppt',
            'pptx',
            'xls',
            'xlsb',
            'xlsm',
            'xlsx'
        ];

        if (in_array($ext, $supported_image)) {
            return 'image';
        }

        if (in_array($ext, $supported_archive)) {
            return 'zip';
        }

        if (in_array($ext, $supported_doc)) {
            return 'doc';
        }

        return 'unknown';
    }
}
