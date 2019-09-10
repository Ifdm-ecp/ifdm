<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProjectCreateRequest extends Request
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
            'project' => 'required|unique:proyectos,nombre,'.$this->get('id'),
            'date' => 'required',
            'PDescription' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'project.required' => 'Project name required',
            'date.required'  => 'Date required',
            'PDescription.required' => 'Project description required',
            'project.unique' => 'Project name has already been taken',
        ];
    }
}
