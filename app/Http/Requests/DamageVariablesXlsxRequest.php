<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DamageVariablesXlsxRequest extends Request
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
            'basinSpreadsheet' => 'required|exists:cuencas,id',
            'fieldSpreadsheet' => 'required|exists:campos,id',
            'uploadedFile'     => 'required'
        ];
    }

    public function messages() {
        return [
            'basinSpreadsheet.required' => 'Basin is required.',
            'fieldSpreadsheet.required' => 'Field is required.',
            'uploadedFile.required'     => 'File to import is required.'
        ];
    }
}
