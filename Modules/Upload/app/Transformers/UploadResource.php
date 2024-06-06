<?php

namespace Modules\Upload\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'url' => count($this->additional) ? $this->getUrl($this?->additional['conversion']) : $this->getUrl(),
            'original_url' => $this->getUrl(),
            'filename' => $this->file_name,
            'mime_type' => $this->mime_type,
            'size' => $this->human_readable_size,
            'order_column' => $this->order_column,
        ];
    }
}
