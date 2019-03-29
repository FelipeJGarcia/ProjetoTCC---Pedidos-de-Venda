<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->validUniqueCustom();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
    *
    * Verifica se um registro existe em uma tabela baseado no id passado como parâmetro
    *
    * @return boolean
    *
    **/
    public function validUniqueCustom(){

        $this->app['validator']->extend('valid_unique_custom', function ($attribute, $value, $parameters){
             
            $status   = true;
            $valor    = $value;
            $table    = $parameters[0]; 
            $campo    = $parameters[1];
            $id       = $parameters[2]; 
            $campo2   = isset($parameters[3]) ? $parameters[3] : null;   
            $valor2   = isset($parameters[4]) ? $parameters[4] : null;  
            $campo3   = isset($parameters[5]) ? $parameters[5] : null;   
            $valor3   = isset($parameters[6]) ? $parameters[6] : null;   

            if(!empty($valor)){

                
                if(!is_null($campo3)){

                    $registro = \DB::table($table)
                       ->where("id","<>",$id)
                       ->where($campo,$valor)
                       ->where($campo2,$valor2)
                       ->where($campo3,$valor3)
                       ->get(); 

                }elseif(is_null($campo2)){

                    $registro = \DB::table($table)
                       ->where("id","<>",$id)
                       ->where($campo,$valor)
                       ->get();   
                
                }else{
                    
                    $registro = \DB::table($table)
                            ->where("id","<>",$id)
                            ->where($campo,$valor)
                            ->where($campo2,$valor2)
                            ->get();   
                }

                if(count($registro) > 0){
                    $status = false;  
                }
            }
            return $status; 
        });     
    }
}
