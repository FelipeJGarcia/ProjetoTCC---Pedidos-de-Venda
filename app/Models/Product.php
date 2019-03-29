<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model{
    
    protected $fillable = [         //Filtro lista branca - colunas q podem ser preenchidas
        'name', 'valor', 'valorMax', 'valorMin', 'description'
    ]; 
  

    public function photos(){     // hasMany relacionamento de 1 para * (um para muitos)
        return $this->hasMany('App\Models\ProductPhoto','product_id');
    }


    //--------------------------Relação de muitos para muitos      // que tipo de modelo pertence muitas vezes: App\pedidoa
    public function pedidos()
    {  
        return $this->belongsToMany('App\Models\Pedido',"itens_pedido","pedido_id","produto_id");

    }
    //------------------------------------------------------
    
    
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
        static::updated(function($product){  
            
           
        });
        static::deleted(function($product){   

            Storage::deleteDirectory("produtos/$product->id"); 
            $product->photos()->delete();

        });

    }    

    /*public function search(Array $data, $totalPorPagina)  // filtro
    {
        $dbug = $this->where(function($query) use ($data)
        {
            if (isset($data['name']))
            {   
                $name = $data['name'];
                $query->whereRaw("name LIKE '%$name%'");  // comparação
            } 
        })//->toSql(); dd($dbug);  //dbug da query q vai ser rodada
        ->paginate($totalPorPagina);
        return $dbug;  
    }*/
    
}