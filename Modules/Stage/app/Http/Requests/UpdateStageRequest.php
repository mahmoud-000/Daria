<?php

namespace Modules\Stage\Http\Requests;

use App\Rules\WithOutSpaces;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateStageRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'     => ['required', 'string', 'min:3', 'max:100', Rule::unique('stages', 'name')->whereNull('deleted_at')->where('pipeline_id', $this->pipeline_id)->ignore($this->stage)],
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
        return auth()->user()->is_owner || Gate::allows('edit-stage');
    }
}
