<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemPedidoFormRequest extends FormRequest
{
    public function authorize()
    {   

        return true;
    }

    public function rules()
    {
        $params     = $this->all();
        $id  = 0;
        $pedido_id   = $params["id"];
  
        return [
            'produto'   => "valid_unique_custom:itens_pedido,produto_id,$id,pedido_id,$pedido_id"
        ];
    }
    
    public function messages() {        //Modificando as mensagens de erro
        return [
            'produto.valid_unique_custom'   => 'Este produto já consta no pedido!'
        ];
    }
}