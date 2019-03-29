<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductPhoto extends Model
{
    protected $fillable = [
        'image','product_id'
    ];
    

    public function product(){
        return $this->belongsTo('App\Models\Product','product_id');
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
        static::updated(function($product){  

           
        });
        static::deleted(function($img){   

            Storage::delete("produtos/".$img->product_id."/".$img->image); 
         
        });

    }
        
    
}
