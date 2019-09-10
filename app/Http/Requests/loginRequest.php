<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class loginRequest extends Request
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
            'username' => 'required|exists:users,name',
            'password' => 'required|exists:users,password',
        ];
    }


    public function messages()
    {
        return [
            'username.required' => 'Username required',
            'password.required'  => 'Password required',
            'username.exists' => 'User or password invalid',
            'password.exists'  => 'User or password invalid',
        ];
    }
}
