<?php

namespace App\Http\Requests\User;

use App\Http\Requests\RequestBase;
use Illuminate\Support\Facades\Auth;

class HolidayStoreRequest extends RequestBase
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
            'holidayDate' => 'required|date',
            'holidayName' => 'required',
        ]);
    }
}
