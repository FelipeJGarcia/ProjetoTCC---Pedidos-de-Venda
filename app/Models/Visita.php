<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Visita extends Model{
    
    protected $fillable = [         //Filtro lista branca - colunas q podem ser preenchidas
        'cliente_id', 'colaborador_id', 'date'
    ]; 

    public function cliente(){
        return $this->belongsTo('App\Models\User','cliente_id');
    }
    public function colaborador(){
        return $this->belongsTo('App\Models\User','colaborador_id');
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
        static::updated(function($visita){  
            
           
        });
        static::deleted(function($visita){   


        });

    }    
    
}