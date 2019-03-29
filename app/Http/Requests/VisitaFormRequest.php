<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitaFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //'name'          => 'required|min:3|max:100'
        ];
    }
    
    public function messages() {        //Modificando as mensagens de erro
        return [
            //'name.required'   => 'O campo nome é obrigatório!'
        ];
    }
}