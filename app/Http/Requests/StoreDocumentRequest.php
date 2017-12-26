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
            //'dni_sender'  => 'digits:8',
            //'name_sender'  => 'required | regex:/(^[A-Za-z ñáéíóú ÑÁÉÍÓÚ ]+$)+/',
            //'patern_sender'  => 'required | regex:/(^[A-Za-z ñáéíóú ÑÁÉÍÓÚ ]+$)+/',
            'ndocSender'  => 'required | regex:/(^[A-Za-z ñáéíóú ÑÁÉÍÓÚ ]+$)+/',
            'ndocAsunto'  => 'required',
            'ndocFecha'  => 'required',
            'ndocReg' => 'required',
            'ndocFolio' => 'required',
        ];
    }

    public function attributes()
    {
        return[
            //'dni_sender'  => 'DNI',
            //'name_sender'  => 'Nombres',
            //'patern_sender'  => 'Apellido Paterno',
            'ndocSender'  => 'Nombre completo del remitente',
            'ndocAsunto'  => 'Asunto',
            'ndocFecha'  => 'Fecha de Presentación',
            'ndocReg' => 'Número de registro en el cuaderno',
            'ndocFolio' => 'Número de folios que contiene el documento',
        ];
    }
}
