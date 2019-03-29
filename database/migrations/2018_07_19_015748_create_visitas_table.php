<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitasTable extends Migration
{
    
    public function up()
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("cliente_id")->unsigned()->index();
            $table->integer("colaborador_id")->unsigned()->index();
            $table->date('date');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('visitas');
    }
}
