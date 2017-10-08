<?php

namespace aidocs\Http\Requests;

use aidocs\Http\Requests\Request;

class DeriveDocumentRequest extends Request
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
            'dep_target'  => 'required',
        ];
    }
}
