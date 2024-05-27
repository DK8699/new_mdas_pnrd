<?php

namespace App\Http\Controllers\NewScheme;

use App\ConfigMdas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NewSchemeModels\ProposalEntity;
use App\CommonModels\Act;
use App\NewSchemeModels\SchemeProposal;
use App\Http\Requests\NewScheme\NewSchemeRequest;
use App\Http\Requests\NewScheme\OtherOptionRequest;
use App\survey\six_finance\SixFinanceFinals;
use Auth;
use DB;

class NewSchemeController extends Controller
{

    public $entities, $financial_year;

    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('users')) {
            return redirect()->route('dashboard');
        }

        $users = $request->session()->get('users');

        if (!$request->session()->exists('six_finance_session_data') || !$request->session()->exists('sixFinanceFinal')) {
            return redirect()->route('dashboard');
        }

        $six_finance_session_data = $request->session()->get('six_finance_session_data');
        $sixFinanceFinal = $request->session()->get('sixFinanceFinal');

        $applicable_id = $six_finance_session_data['applicable_id'];
        $applicable_name = $six_finance_session_data['applicable_name'];
        $entities = ProposalEntity::select('id', 'entity_name')->where([
            'is_active' => 1
        ])->get();
        $is_active_count = $this->check_pending_other_option($users->employee_code);
        $financial_years = Act::where('id', '>', 5)->select('id', 'financial_year')->get();
        $this->entities = $entities;
        $this->financial_year = $financial_years;

        //--------------------------------------------------------------------------------------------------------------
        //----------------------------------<<    AFTER FILL UP     >>--------------------------------------------------
        //--------------------------------------------------------------------------------------------------------------

        $dataFillFinal = NULL;
        $alreadySubmitted = NULL;
        $count = SchemeProposal::where([
            'six_finance_final_id' => $sixFinanceFinal->id
        ])->count();

        if ($count > 0) {
            $dataFill = SchemeProposal::where([
                ['six_finance_final_id', '=', $sixFinanceFinal->id]
            ])->select('*')->get();

            foreach ($dataFill AS $li) {
                $dataFillFinal["E_" . $li->entity_id]["A_" . $li->act_id] = $li->estimated_cost;
            }

            $alreadySubmitted = 1;
        }

        return view('newscheme.new_scheme', compact('entities', 'financial_years', 'is_active_count', 'applicable_name', 'alreadySubmitted', 'dataFillFinal'));
    }

    private function check_pending_other_option($employee_code)
    {
        return ProposalEntity::where([
            'employee_code' => $employee_code,
            'is_active' => 0,
            'cancel' => 0
        ])->count();
    }

    public function save_proposal_entities(NewSchemeRequest $request)
    {
        if (!$request->session()->exists('users')) {
            return redirect()->route('dashboard');
        }

        $users = $request->session()->get('users');

        if (!$request->session()->exists('six_finance_session_data') || !$request->session()->exists('sixFinanceFinal')) {
            return redirect()->route('dashboard');
        }

        $six_finance_session_data = $request->session()->get('six_finance_session_data');
        $sixFinanceFinal = $request->session()->get('sixFinanceFinal');

        $applicable_id = $six_finance_session_data['applicable_id'];
        $applicable_name = $six_finance_session_data['applicable_name'];
        $proposal_entity_details = $this->get_proposal_entity_details();
        $entities = $proposal_entity_details[0];
        $financial_years = $proposal_entity_details[1];


        if ($this->get_user_request(Auth::user()->username) > 0) {
            return response()->json([
                'msg' => 'You have requested for new scheme proposal . Please consult administrator for submitting proposal data.'
            ]);
        }

        DB::beginTransaction();

        try {

            $count = SchemeProposal::where([
                'six_finance_final_id' => $sixFinanceFinal->id
            ])->count();

            if ($count > 0) {
				
                //------------------- CHECK CONFIG RULES ----------------------------

                if(isset(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date)){
                    if(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date < Carbon::now()->format("Y-m-d")){
                        return response()->json([
                            'msg' => 'Resubmit is currently suspended. Please contact admin for more details.'
                        ]);
                    }
                }

                $resubmitTracker=DB::table('six_finance_resubmit_trackers')->where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                    ['r_date', '=', Carbon::now()->format("Y-m-d")],
                ])->count();

                if($resubmitTracker >= 10){
                    DB::rollback();
                    return response()->json([
                        'msg' => 'You have crossed the maximum number of resubmit that is 10 times per day. Kindly contact admin for more details!'
                    ]);
                }

                //------------------- CHECK CONFIG RULES ENDED ----------------------

                $sixFinanceTest=SixFinanceFinals::where([
                    ['id', '=', $sixFinanceFinal->id]
                ])->first();

                if(!$sixFinanceTest){
                    DB::rollback();
                    return response()->json([
                        'msg' => 'Oops! Something went wrong.Please try again later'
                    ]);
                }elseif($sixFinanceTest->verify==1){
                    DB::rollback();
                    return response()->json([
                        'msg' => 'Sorry you can not resubmit the form because your form is verified. Please ask the admin for more details.'
                    ]);
                }

                $delete = SchemeProposal::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                if (!$delete) {
                    return response()->json([
                        'msg' => 'Oops! Something went wrong. Please try again later.#d10'
                    ]);
                }
				
				
				DB::table('six_finance_resubmit_trackers')->insert(
                ['six_finance_final_id' => $sixFinanceFinal->id, 'r_date' => Carbon::now()->format("Y-m-d")]
				);
            }


            foreach ($entities as $value) {
                foreach ($financial_years as $value1) {
                    $proposal = new SchemeProposal;
                    $proposal->six_finance_final_id = $sixFinanceFinal->id;
                    $proposal->applicable_id = $applicable_id;
                    $proposal->act_id = $value1['id'];
                    $proposal->entity_id = $value['id'];
                    $proposal->estimated_cost = $request->input('estimated_cost' . $value['id'] . "" . $value1['id']);
                    $proposal->save();
                }
            }
            SixFinanceFinals::where('id', $sixFinanceFinal->id)
                ->update(['five_year_info' => 1]);

        }catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'msg' => 'Opps! Something went worng.'
            ]);
        }

        DB::commit();
        return response()->json([]);
    }

    public function add_other_options(OtherOptionRequest $request)
    {
        if (!$request->session()->exists('users')) {
            return redirect()->route('dashboard');
        }

        $users = $request->session()->get('users');
        if (!$request->session()->exists('six_finance_session_data') || !$request->session()->exists('sixFinanceFinal')) {
            return redirect()->route('dashboard');
        }

        $six_finance_session_data = $request->session()->get('six_finance_session_data');
        $sixFinanceFinal = $request->session()->get('sixFinanceFinal');

        $applicable_id = $six_finance_session_data['applicable_id'];
        $applicable_name = $six_finance_session_data['applicable_name'];
        $save_status = 0;
        DB::transaction(function () use ($request, &$save_status, $users) {
            $entity = new ProposalEntity;
            $entity->entity_name = $request->input('other_option_name');
            $entity->employee_code = $users->employee_code;
            $save_status = $entity->save();
        });
        if ($save_status > 0) {
            return redirect()->route('survey.six_finance_form_dashboard');
        } else {
            return redirect('/new_scheme')->with('error', 'New other option is successfully saved. Please wait por its approval.');
        }
    }

    private function get_proposal_entity_details()
    {
        $entities = ProposalEntity::select('id', 'entity_name')->where([
            'is_active' => 1
        ])->get();
        $financial_years = Act::where('id', '>', 5)->select('id', 'financial_year')->get();
        return [
            $entities,
            $financial_years
        ];
    }

    private function get_user_request($employeeCode)
    {
        return ProposalEntity::where([
            'is_active' => 0,
            'cancel' => 0,
            'employee_code' => $employeeCode
        ])->count();
    }

}
