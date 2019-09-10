<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class updateRequest extends Request
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
        $rules = [
            'Charge' => 'required',
            'Company' => 'required',
            'Profile' => 'required',
            'Gender' => 'required',
            'Email' => 'required|email',
            'password' => 'confirmed',    
        ];


        if ($this->input('password') != '' | $this->input('password') != null) {
            $rules['password_confirmation'] = 'required';
        }

        return $rules;
    }


    public function messages()
    {
        $messages = [
            'Charge.required'  => 'Position required',
            'Company.required' => 'Company description required',
            'Profile.required' => 'Profile required',
            'Gender.required' => 'Gender required',
            'Email.required' => 'E-mail required',

            'Email.email' => 'E-mail must be a valid e-mail address.',
            'password.confirmed' => 'password does not match',
        ];

        if ($this->input('password') != '' | $this->input('password') != null) {
           $messages['password_confirmation.required'] = 'please, confirm the password is required';
        }

        return $messages;
    }
}
