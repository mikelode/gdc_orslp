<?php

namespace aidocs\Http\Requests;

use aidocs\Http\Requests\Request;

class StoreDocumentRequest extends Request
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
            'dni_sender'  => 'digits:8',
            'name_sender'  => 'required | regex:/(^[A-Za-z ñáéíóú ÑÁÉÍÓÚ ]+$)+/',
            'patern_sender'  => 'required | regex:/(^[A-Za-z ñáéíóú ÑÁÉÍÓÚ ]+$)+/',
            'matern_sender'  => 'required | regex:/(^[A-Za-z ñáéíóú ÑÁÉÍÓÚ ]+$)+/',
            'subject_doc'  => 'required',
            'date_doc'  => 'required',
            'nreg_doc' => 'required',
        ];
    }

    public function attributes()
    {
        return[
            'dni_sender'  => 'DNI',
            'name_sender'  => 'Nombres',
            'patern_sender'  => 'Apellido Paterno',
            'matern_sender'  => 'Apellido Materno',
            'subject_doc'  => 'Asunto',
            'date_doc'  => 'Fecha de Presentación',
            'nreg_doc' => 'Número de registor en el cuaderno',
        ];
    }
}
