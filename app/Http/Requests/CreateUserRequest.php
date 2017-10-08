<?php

namespace aidocs\Http\Requests;

use aidocs\Http\Requests\Request;

class CreateUserRequest extends Request
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
            'dni_user'  =>  'required | digits:8',
            'name_user'  =>  'required | regex:/(^[A-Za-z ñáéíóú ÑÁÉÍÓÚ ]+$)+/',
            'patern_user'  =>  'required | regex:/(^[A-Za-z ñáéíóú ÑÁÉÍÓÚ ]+$)+/',
            'matern_user'  =>  'required | regex:/(^[A-Za-z ñáéíóú ÑÁÉÍÓÚ ]+$)+/',
        ];
    }
}
