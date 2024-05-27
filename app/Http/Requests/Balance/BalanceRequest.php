<?php

namespace App\Http\Requests\Balance;

use Illuminate\Foundation\Http\FormRequest;
use App\CommonModels\Act;
class BalanceRequest extends FormRequest
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
        $rules = [];
        $financial_years = Act::where('id', '<', 5)->select('id', 'financial_year')->get();
        $balance_type = [
            'Opening balance at the begining of the year',
            'Inflow during the year(as the item 13 above)',
            'Outflow during the year(as the item 18 above)'
        ];
        foreach ($financial_years as $value) {
            for($i = 0; $i < count($balance_type); $i++){
                if($i == 0){
                    $rules['balance'.$i.''.$value['id']] = 'required|integer';
                }
            }
        }
        return $rules;
    }
    public function messages() {
        $message = [];
        $financial_years = Act::where('id', '<', 5)->select('id', 'financial_year')->get();
        $balance_type = [
            'Opening balance at the begining of the year',
            'Inflow during the year(as the item 13 above)',
            'Outflow during the year(as the item 18 above)'
        ];
        foreach ($financial_years as $value) {
            for($i = 0; $i < count($balance_type); $i++){
                if($i == 0){
                    $message['balance'.$i.''.$value['id'].'.required'] = 'Opening balance at the begining';
                    $message['balance'.$i.''.$value['id'].'.integer'] = 'Opening balance at the begining must be numeric.';
                   
                }
            }
        }
        return $message;
    }
}
