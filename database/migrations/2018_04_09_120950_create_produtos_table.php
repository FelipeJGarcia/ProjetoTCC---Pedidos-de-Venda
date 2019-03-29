<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutosTable extends Migration
{
    
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('image', 200);
            $table->float('valor');
            $table->text('description');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
