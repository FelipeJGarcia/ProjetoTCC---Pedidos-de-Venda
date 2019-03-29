<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest{
    
    public function authorize(){
        
        return true;        //se ficar false ninguem faz alteração
    }

    
    public function rules(){

        $params = $this->all();
        $id     = isset($params["id"]) ? $params["id"] : 0;
        
        return [            //regras de validação dos campos
            'name'         => "required|min:2|max:250|unique:products,name,$id",
            'valor'        => 'required|numeric',
            'valorMin'     => 'required|numeric',
            'valorMax'     => 'required|numeric',
        ];
    }
    
    public function messages() {        //Modificando as mensagens de erro
        return [
            //NOME
            'name.required'   => 'O campo (Nome) é obrigatório!',
            'name.unique'     => 'O valor informado para o campo (Nome) já esta em uso!',
            'name.min'        => 'O campo (Nome) deve conter no mínimo 2 caracteres!',
            'name.max'        => 'O campo (Nome) deve conter no máximo 50 caracteres!',
            //VALOR DE CUSTO
            'valor.required'    => 'O campo (Valor de Custo) é obrigatório!',
            'valor.numeric'     => 'O campo (Valor de Custo) deve conter apenas números! Não utilize virgula, apenas ponto.',
            //VALOR MINIMO
            'valorMin.required'    => 'O campo (Valor Mínimo) é obrigatório!',
            'valorMin.numeric'     => 'O campo (Valor Mínimo) deve conter apenas números! Não utilize virgula, apenas ponto.',
            //VALOR MAXIMO
            'valorMax.required'    => 'O campo (Valor Máximo) é obrigatório!',
            'valorMax.numeric'     => 'O campo (Valor Máximo) deve conter apenas números! Não utilize virgula, apenas ponto.',
        ];
    }
}
