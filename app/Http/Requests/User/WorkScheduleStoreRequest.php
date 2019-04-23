<?php

namespace App\Http\Requests\User;

use App\Http\Requests\RequestBase;
use Illuminate\Support\Facades\Auth;

class WorkScheduleStoreRequest extends RequestBase
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
            'projectIds' => 'required',
            'projectIds.*' => 'required',
            'workschedules' => 'required',
            'workschedules.*.id' => 'nullable',
            'workschedules.*.week_number' => 'nullable',
            'workschedules.*.workdate' => 'nullable',
            'workschedules.*.is_paid_holiday' => 'nullable',
            'workschedules.*.starttime_hh' => 'nullable',
            'workschedules.*.starttime_mm' => 'nullable',
            'workschedules.*.endtime_hh' => 'nullable',
            'workschedules.*.endtime_mm' => 'nullable',
            'workschedules.*.breaktime' => 'nullable',
            'workschedules.*.breaktime_midnight' => 'nullable',
            'workschedules.*.worktime.*' => 'nullable',
            'workschedules.*.detail' => 'nullable',
        ]);
    }
}
