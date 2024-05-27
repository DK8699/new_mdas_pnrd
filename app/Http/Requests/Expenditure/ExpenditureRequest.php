<?php

namespace App\Http\Requests\Expenditure;

use Illuminate\Foundation\Http\FormRequest;
use App\ExpenditureModels\ExpenditureCategory;
use App\CommonModels\Act;

class ExpenditureRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $ependiture_details = $this->get_expenditure_details();
        $expenditure = $ependiture_details[0];
        $financial_years = $ependiture_details[1];
        $rules = [];
        $i = 0;
        foreach ($expenditure as $value) {
            foreach ($financial_years as $value1) {
                $rules['expenditure' . $value['expenditure'] . "" . $value['category'] . "" . $value1['id']] = 'required|numeric|min:0';
            }
        }
        foreach ($financial_years as $value1) {
            $rules['expenditure2' . $value1['id']] = 'required|numeric|min:0';
        }
        return $rules;
    }

    public function messages() {
        $ependiture_details = $this->get_expenditure_details();
        $expenditure = $ependiture_details[0];
        $financial_years = $ependiture_details[1];
        $message = [];
        foreach ($expenditure as $value) {
            foreach ($financial_years as $value1) {
                $message['expenditure' . $value['expenditure'] . "" . $value['category'] . "" . $value1['id'] . ".required"] = 'Expenditure value is required to be filled.';
                $message['expenditure' . $value['expenditure'] . "" . $value['category'] . "" . $value1['id'] . ".numeric"] = 'Expenditure value must be positive values.';
                $message['expenditure' . $value['expenditure'] . "" . $value['category'] . "" . $value1['id'] . ".min"] = 'Expenditure value must be positive values.';
            }
        }
        foreach ($financial_years as $value1) {
            $message['expenditure2'. $value1['id'] . ".required"] = 'Expenditure value is required to be filled.';
            $message['expenditure2'. $value1['id'] . ".numeric"] = 'Expenditure value must be positive values.';
            $message['expenditure2'. $value1['id'] . ".min"] = 'Expenditure value must be positive values.';
        }
        return $message;
    }

    private function get_expenditure_details() {
        $expenditure = ExpenditureCategory::join('category_expenditures', 'expenditure_categories.id', '=', 'category_expenditures.category_id')
                ->join('expenditures', 'expenditures.id', '=', 'category_expenditures.expenditure_id')
                ->select('category_expenditures.id AS category_expenditure_id', 'expenditures.id AS expenditure', 'expenditure_categories.id AS category', 'expenditure_name', 'category_name')
                ->where([
                    ['expenditure_categories.id','!=',2],
                    ['is_active','=',1]
                ])
                ->get();
        $financial_years = Act::where('id', '<=', 5)->select('id', 'financial_year')->get();
        $category = ExpenditureCategory::select('id', 'category_name')->where('id','!=',2)->get();
        return [
            $expenditure,
            $financial_years,
            $category
        ];
    }

}
