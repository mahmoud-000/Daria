<?php

namespace Modules\Pipeline\Http\Requests;

use App\Rules\WithOutSpaces;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StorePipelineRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('pipelines', 'name')->withoutTrashed()->ignore($this->pipeline)],
            'app_name'          => ['required', 'string'],
            'is_active'     => ['required', 'boolean'],
            'remarks'       => ['string', 'nullable'],

            'stages'            => ['required', 'array', 'present'],
            'stages.*.name'     => ['distinct', 'required', 'string', 'min:3', 'max:100', Rule::unique('stages', 'name')->withoutTrashed()->whereNull('pipeline_id')],
            'stages.*.complete' => ['distinct', 'required', 'numeric', 'min:0', 'max:100'],
            'stages.*.color'    => ['required', 'string', new WithOutSpaces],
            'stages.*.is_default'  => ['sometimes', 'boolean'],
            'stages.*.is_active'     => ['required', 'boolean'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('create-pipeline');
    }
}
