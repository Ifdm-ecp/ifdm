<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PLTRequest extends Request
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
            'pltqo'=> 'required|max:100',
            'pltqg'=> 'required|max:100',
            'pltqw'=> 'required|max:100',
            'ProdT'=> 'required',
        ];
    }
}
