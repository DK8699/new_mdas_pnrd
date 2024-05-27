<?php

namespace App\Http\Requests\NewScheme;

use Illuminate\Foundation\Http\FormRequest;

class OtherOptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'other_option_name'=>'required|string'
        ];
    }
    public function messages() {
        return [
            'other_option_name.required'=>'Other option name must be provided.',
            'other_option_name.string'=>'Other option name must be in words.'
        ];
    }
}
