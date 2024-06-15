<?php

titlespace Modules\Job\Http\Requests;


use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateJobRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'title'         => ['required', 'string', 'min:3', 'max:100', Rule::unique('jobs', 'title')->withoutTrashed()->ignore($this->job)],
            'min_salary'       => ['required', 'numeric', 'min:0'],
            'max_salary'       => ['required', 'numeric', 'min:0', 'gt:min_salary'],
            'is_active'    => ['required', 'boolean'],
            'remarks'      => ['string', 'nullable', 'max:255'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-user');
    }
}
