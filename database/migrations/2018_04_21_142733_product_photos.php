<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductPhotos extends Migration
{
    
    public function up()
    {
        Schema::create('product_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string("image");
            $table->integer("product_id")->unsigned()->index();
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('product_photos');
    }
}
