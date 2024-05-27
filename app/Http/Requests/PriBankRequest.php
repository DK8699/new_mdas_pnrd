<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class PriBankRequest extends FormRequest
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
            'bank_name' => 'required|numeric|min:1|max:1348',
            'acc_no' => 'required',
            'ifsc' => 'required|alpha_num|size:11',
            'branch_name' => 'required|string',
            'bank_image' => 'required|mimes:jpg,jpeg,pdf,png|min:100|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'bank_name.required' => 'Please select a bank',
            'bank_name.numeric' => 'Please select a valid bank',
            'bank_name.min' => 'Please select a valid bank',
            'bank_name.max' => 'Please select a valid bank',
            'acc_no.required' => 'Please provide an account no',
            'ifsc.required' => 'Please provide an IFSC',
            'ifsc.alpha_num' => 'Please provide in alpha numeric',
            'ifsc.size' => 'Please provide a valid size',

            'branch_name.required' => 'Please provide a Bank name',
            'branch_name.string' => 'Please provide a proper branch name',
            'bank_image.required' => 'Please provide a bank passbook',
            'bank_image.mimes' => 'Please provide valid format of passbook (jpg,jpeg,pdf,png)',
            'bank_image.min' => 'Please provide a min 500kb file',
            'bank_image.max' => 'Please provide a max 1 MB file',
        ];
    }
}