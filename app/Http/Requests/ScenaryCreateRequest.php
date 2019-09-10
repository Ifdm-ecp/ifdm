<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class ScenaryCreateRequest extends Request
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
        $validations_ = [
            'scenary' => 'required',
            'SDescription' => 'required',
            'type' => 'required',
            'basin' => 'required',
            'field' => 'required',
            'well' => 'required',
            'date' => 'required',
            'project' => 'required',
        ];

        if (isset($_POST['type']) && $_POST['type'] == 'IPR') {
            $validations_ = array_merge($validations_,['formation_ipr' => 'required']);
        } else {
            $validations_ = array_merge($validations_,['formation' => 'required']);
        }

        return $validations_;
    }

    public function messages()
    {
        return [
            'scenary.required' => 'Scenary name required',
            'SDescription.required'  => 'Scenary description required',
            'type.required' => 'Type required',
            'basin.required' => 'Basin name required',
            'field.required'  => 'Field required',
            'well.required' => 'Well name required',
            'formation.required' => 'Formation name required',
            'formation_ipr.required' => 'Formation name required',
            'date.required' => 'Date required',
            'project.required' => 'Project required',
            'scenary.unique' => 'Scenary name has already been taken',
        ];
    }
}
