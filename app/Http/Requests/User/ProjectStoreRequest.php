<?php

namespace App\Http\Requests\User;

use App\Http\Requests\RequestBase;
use Illuminate\Support\Facades\Auth;

class ProjectStoreRequest extends RequestBase
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
            'projectCode' => 'required|max:5',
            'projectName' => 'required',
            'categoryId' => 'required|numeric',
            'companyId' => 'required|numeric',
            'userId' => 'required|numeric',
            'projectStatusId' => 'required|numeric',
        ]);
    }
}
