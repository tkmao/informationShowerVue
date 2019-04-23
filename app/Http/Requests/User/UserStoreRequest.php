<?php

namespace App\Http\Requests\User;

use App\Http\Requests\RequestBase;
use Illuminate\Support\Facades\Auth;

class UserStoreRequest extends RequestBase
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
            'userName' => 'required',
            'userEmail' => 'required',
            'userPassword' => 'required',
            'userTypeId' => 'required|numeric',
            'workingtimeType' => 'nullable|numeric',
            'worktimeDay' => 'nullable|numeric',
            'maxWorktimeMonth' => 'nullable|numeric',
            'workingtimeMin' => 'nullable|numeric',
            'workingtimeMax' => 'nullable|numeric',
            'paidHoliday' => 'required|numeric',
            'hiredate' => 'required|numeric',
            'userIsAdmin' => 'required',
        ]);
    }
}
