<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItensPedidoTable extends Migration
{
    public function up()
    {
        Schema::create('itens_pedido', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("pedido_id")->unsigned()->index();
            $table->integer("produto_id")->unsigned()->index();
            $table->integer("quantidade");
            $table->float("valor");
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('itens_pedido');
    }
}
