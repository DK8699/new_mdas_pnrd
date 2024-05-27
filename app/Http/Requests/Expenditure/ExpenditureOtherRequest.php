<?php

namespace App\Http\Requests\Expenditure;

use Illuminate\Foundation\Http\FormRequest;

class ExpenditureOtherRequest extends FormRequest
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
            'category'=>'required|numeric',
            'other_specify'=>'required|string'
        ];
    }
    
    public function message() {
        return [
            'category.require'=>'Category must be selected.',
            'other_specify.required'=>'Other specification is required to specified.'
        ];
    }
}
