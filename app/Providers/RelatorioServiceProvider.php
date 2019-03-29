<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use App\Models\Pedido;

class RelatorioServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
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
    * Retorna a quantidade pedidos por mes do ano atual
    *
    *
    *
    **/ 
    public static function quantidadePedidosMesAnoAtual(){

        $data = array();
        $mes  = "";

        for ($i=1; $i <= 12; $i++) { 
           
           $mes   = $i < 10 ? "0".$i : $i;
           $where = "EXTRACT(MONTH FROM p.date) = '{$mes}' and EXTRACT(YEAR FROM p.date) = YEAR(CURDATE())";

           $data[] = \DB::table("pedidos as p")
                     ->selectRaw("COUNT(id) as total") 
                     ->whereRaw($where)
                     ->get()[0]->total;
                     


        }

        return $data;


    }


}
