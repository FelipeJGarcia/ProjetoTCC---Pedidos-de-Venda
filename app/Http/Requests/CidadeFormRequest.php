<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CidadeFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $params = $this->all();
        $id     = isset($params["id"]) ? $params["id"] : 0;

        return [
            'name'          => "required|min:2|max:50|unique:cidades,name,$id"
        ];
    }
    
    public function messages() {        //Modificando as mensagens de erro
        return [
            'name.required'   => 'O campo (Nome) é obrigatório!',
            'name.unique'     => 'O valor informado para o campo (Nome) já esta em uso!',
            'name.min'        => 'O campo (Nome) deve conter no mínimo 2 caracteres!',
            'name.max'        => 'O campo (Nome) deve conter no máximo 50 caracteres!'
        ];
    }
}