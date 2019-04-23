<?php

namespace App\Http\Requests\User;

use App\Http\Requests\RequestBase;
use Illuminate\Support\Facades\Auth;

class WeeklyReportStoreRequest extends RequestBase
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRules([
            'weekly_report_id' => 'nullable',
            'user_id' => 'required|numeric',
            'is_subumited' => 'nullable',
            'targetweek' => 'required|numeric',
            'project_id' => 'nullable',
            'nextweek_schedule' => 'nullable',
            'thismonth_dayoff' => 'nullable',
            'site_information' => 'nullable',
            'opinion' => 'nullable',
            'submit_type' => 'required',
        ]);
    }
}
