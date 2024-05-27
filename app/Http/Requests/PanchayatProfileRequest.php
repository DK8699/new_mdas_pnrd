<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class PanchayatProfileRequest extends FormRequest
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
        $questionCount = DB::table('panchayat_profile_questions')->count();

        return [
            'answer1' => 'required|numeric|min:0|max:1',
            'answer2' => 'required|numeric|min:0|max:1',
            'answer3' => 'required|numeric|min:0|max:1',
            'answer4' => 'required|numeric|min:0|max:1',
            'ISP-name' => 'nullable|required_if:answer4,1|numeric|min:1|max:5',
            'other_isp_name' => 'nullable|required_if:ISP-name,5|string'
        ];
    }

    public function messages()
    {
        $questionCount = DB::table('panchayat_profile_questions')->count();
        $validation = [];


        return [
            'answer1.required' => 'Please provide the answer.',
            'answer1.numeric' => 'Please provide valid answer.',
            'answer1.min' => 'Minimum 0',
            'answer1.max' => 'Maximum 1',

            'answer2.required' => 'Please provide the answer',
            'answer2.numeric' => 'Please provide valid answer',
            'answer2.min' => 'Minimum 0',
            'answer2.max' => 'Maximum 1',

            'answer3.required' => 'Please provide the answer.',
            'answer3.numeric' => 'Please provide valid answer.',
            'answer3.min' => 'Minimum 0',
            'answer3.max' => 'Maximum 1',

            'answer4.required' => 'Please provide the answer.',
            'answer4.numeric' => 'Please provide valid answer.',
            'answer4.min' => 'Minimum 0',
            'answer4.max' => 'Maximum 1',

            'ISP-name.required_if' => 'ISP needs to be Selected',
            'ISP-name.numeric' => 'Please provide valid answer',
            'ISP-name.min' => 'ISP to be min 1',
            'ISP-name.max' => 'ISP to be max 5',
            'other_isp_name.required_if' => 'When Others is selected',
            'other_isp_name.string' => 'Must be a string',
        ];
    }
}