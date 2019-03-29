<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValorTotalTableItensPedido extends Migration
{
    
    public function up()
    {
        Schema::table('itens_pedido', function (Blueprint $table) {
            $table->float('valorTotal')->nullabre()->after('valor');
        });
    }

    

    public function down()
    {
        Schema::table('itens_pedido', function (Blueprint $table) {
            $table->dropColumn('valorTotal');
        });
    }
}
