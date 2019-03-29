<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
            'name'          => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50',
            'cpf'           => "required|formato_cpf_cnpj|unique:users,cpf,$id",
            'telefone1'     => 'required|min:11|max:14',
            'bairro'        => 'required',
            'rua'           => 'required',
            'numero'        => 'required|numeric'
        ];
    }
    
    public function messages() {        //Modificando as mensagens de erro
        return [
            //NOME
            'name.required'   => 'O campo (Nome) é obrigatório!',
            'name.min'        => 'O campo (Nome) deve conter no mínimo 2 caracteres!',
            'name.max'        => 'O campo (Nome) deve conter no máximo 50 caracteres!',
            'name.regex'      => 'O campo (Nome) deve conter apenas letras!',
            //CPF
            'cpf.required'         => 'O campo (CNPJ ou CPF) é obrigatório!',
            'cpf.formato_cpf_cnpj' => 'O campo (CNPJ ou CPF) é inválido! Use o seguinte formato: [ CNPJ( 99.999.999/9999-99 ) ou CPF( 999.999.999-99 ) ]',
            'cpf.unique'           => 'O valor informado para o campo (CNPJ ou CPF) já esta em uso!',
            //TELEFONE 1
            'telefone1.required' => 'O campo (Telefone 1) é obrigatório!',
            'telefone1.min'      => 'O campo (Telefone 1) é inválido! Use o seguinte formato: [ (99)99999-9999 ]',
            'telefone1.max'      => 'O campo (Telefone 1) é inválido! Use o seguinte formato: [ (99)99999-9999 ]',
            //BAIRRO
            'bairro.required' => 'O campo (Bairro) é obrigatório!',
            //RUA
            'rua.required' => 'O campo (Rua) é obrigatório!',
            //NUMERO
            'numero.required' => 'O campo (Numero) é obrigatório!',
            'numero.numeric'  => 'O campo (Numero) deve conter apenas números!'
        ];
    }
}
