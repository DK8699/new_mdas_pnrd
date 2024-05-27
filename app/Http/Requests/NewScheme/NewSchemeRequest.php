<?php

namespace App\Http\Requests\NewScheme;

use Illuminate\Foundation\Http\FormRequest;
use App\NewSchemeModels\ProposalEntity;
use App\CommonModels\Act;

class NewSchemeRequest extends FormRequest
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
        $proposal_entity_details = $this->get_proposal_entity_details();
        $entities = $proposal_entity_details[0];
        $financial_years = $proposal_entity_details[1];
        foreach ($entities as $value) {
            foreach ($financial_years as $value1) {
                $rules['estimated_cost'.$value['id'].$value1['id']] = 'required|numeric|min:0';
            }
        }
        return $rules;
    }
    public function messages() {
        $messages = [];
        $proposal_entity_details = $this->get_proposal_entity_details();
        $entities = $proposal_entity_details[0];
        $financial_years = $proposal_entity_details[1];
        foreach ($entities as $value) {
            foreach ($financial_years as $value1) {
               $messages['estimated_cost'.$value['id'].$value1['id'].'.required'] = 'Estimated cost must be filled.';
               $messages['estimated_cost'.$value['id'].$value1['id'].'.numeric'] = 'Estimated cost must be a number.';
               $messages['estimated_cost'.$value['id'].$value1['id'].'.min'] = 'Estimated cost cannot be a negative value.';
            }
        }
        return $messages;
    }
    private function get_proposal_entity_details() {
        $entities = ProposalEntity::select('id', 'entity_name')->where([
                    'is_active' => 1
                ])->get();
        $financial_years = Act::where('id', '>', 5)->select('id', 'financial_year')->get();
        return [
            $entities,
            $financial_years
        ];
    }
}
