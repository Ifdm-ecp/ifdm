<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class registerRequest extends Request
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
        return [
            'Name' => 'required|unique:users,name',
            'Charge' => 'required',
            'fullName' => 'required',
            'Company' => 'required',
            'Profile' => 'required',
            'Gender' => 'required',
            'Password' => 'required|same:PasswordC|min:6',
            'PasswordC' => 'required',
            'Email' => 'required|email',
        ];
    }


    public function messages()
    {
        return [
            'Name.required' => 'User name required',
            'fullName.required' => 'Name required',
            'Charge.required'  => 'Position required',
            'Company.required' => 'Company description required',
            'Profile.required' => 'Profile required',
            'Gender.required' => 'Gender required',
            'Email.required' => 'E-mail required',
            'Password.required' => 'Password required',
            'PasswordC.required' => 'Confirm password required',
       
            'Password.same' => 'Password and confirm password must match.',
            'Password.min' => 'Password must be at least 6 characters.',

            'Email.email' => 'E-mail must be a valid e-mail address.',

            'Name.unique'  => 'User name has already been taken.',
        ];
    }
}
