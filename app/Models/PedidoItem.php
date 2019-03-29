<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{

    protected $table = "itens_pedido";

    protected $fillable = [
    	"pedido_id",
    	"produto_id",
    	"quantidade",
    	"valor"
    ];

    public function pedido()
    {
        return $this->belongsT0('App\Models\PedidoItem',"pedido_id");
    }

}
