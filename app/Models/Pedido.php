<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [         //lista branca - colunas q podem ser preenchidas
        'colaborador_id', 'cliente_id', 'status', 'date', 'observacao'
    ];
    //--------------------------Relação de muitos para muitos      // que tipo de modelo pertence muitas vezes: App\pedidoa
    public function produtos()
    {
        return $this->belongsToMany('App\Models\Product',"itens_pedido","produto_id","pedido_id");
    }
    public function itens()
    {
        return $this->hasMany('App\Models\PedidoItem',"pedido_id");
    }
    public function cliente(){
        return $this->belongsTo('App\Models\User','cliente_id');
    }
    public function colaborador(){
        return $this->belongsTo('App\Models\User','colaborador_id');
    }


    public static function boot(){
        
  
        parent::boot();
        static::creating(function($post){   

        });
        static::updating(function($post){


        });
        static::deleting(function($post){ 

        
        });
        static::created(function($post){   
            
       

        });
        static::updated(function($pedido){  
            
           
        });
        static::deleted(function($pedido){   

            $pedido->itens()->delete();

        });
    }

    /** **/
    public static function total($id){

        $select = "SUM(ip.valor*ip.quantidade) as total";

        $total = \DB::table("itens_pedido as ip")->selectRaw($select)
            ->where("ip.pedido_id",$id)
            ->get(); 

        if(!is_null($total)){
            return $total[0]->total;
        }else{
            return 0;
        }    

    }

    public static function totalBase($id){

        $select = "SUM(p.valor * ip.quantidade) as total";

        $valor = \DB::table('itens_pedido as ip')->selectRaw($select)
        ->join("products as p","p.id","=","ip.produto_id")
        ->where("ip.pedido_id",$id)
        ->get();

        return $valor[0]->total;
    }

    public static function itensPedido($id){

        $select = "ip.id, ip.valor, ip.quantidade, p.name, ip.produto_id";

        $itens = \DB::table("itens_pedido as ip")->selectRaw($select)
            ->join("products as p","p.id","=","ip.produto_id")
            ->where("ip.pedido_id",$id)
            ->get(); 

        return $itens;

    }


    public static function rulesItem(){

        return array(
            "quantidade" => "required|integer",
            "valor"      => "required|numeric",
            "produto_id" => "required",
            "pedido_id"  => "required"
        );

    }
    
    public static function messagesItem(){

        return array(
            "quantidade.required" => "- A quantidade é obrigatória.",
            "quantidade.integer"  => "- A quantidade deve ser inteira.",
            "valor.required"      => "- Informe o valor.",
            "valor.numeric"       => "- O valor deve ser um número.",
            "pedido_id.required"  => "- O ID do pedido não foi informado.",
            "produto_id.required" => "- O ID do produto não foi informado."      
        );

    }
    
}
