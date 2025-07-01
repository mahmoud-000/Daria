<?php

namespace Modules\Stage\Http\Requests;

use App\Rules\WithOutSpaces;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreStageRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'     => ['required', 'string', 'min:3', 'max:100', Rule::unique('stages', 'name')->withoutTrashed()->where('pipeline_id', $this->pipeline_id)],
            'complete' => ['required', 'numeric', 'min:0', 'max:100'],
            'color'    => ['required', 'string', new WithOutSpaces],
            'is_default'  => ['sometimes', 'boolean'],
            'is_active'     => ['required', 'boolean'],
            'pipeline_id'       => ['required', 'integer'],
            'remarks'       => ['string', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-stage');
    }
}
