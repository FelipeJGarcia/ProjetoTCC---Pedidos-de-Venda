<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->integer("pessoa_id")->unsigned()->index();
            $table->float('valorTotal');
            $table->text('observacao');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
