<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MultiparametricStatisticalCreateRequest extends Request
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
        if (request()->statistical == 'Colombia') {
            return [
                'statistical' => 'required|in:Colombia',
            ];
        } else {
            return [
                'basin_statistical' => 'required|exists:cuencas,id',
                'field_statistical' => 'required|exists:campos,id',
            ];
        }
    }

    public function messages()
    {
        return [
            'statistical.required' => 'The checkbox needs to be checked.',
            'statistical.in' => 'The data provided by the checkbox is not correct.',
            'basin_statistical.required' => 'Basin is required.',
            'field_statistical.required' => 'Field is required.',

            'basin_statistical.exists' => "This Basin doesn't exist.",
            'field_statistical.exists' => "This Field doesn't exist.",
        ];
    }
}
