<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValoresTableProducts extends Migration
{
    
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->float('valorMax')
                ->nullabre()
                ->after('valor');
            $table->float('valorMin')
                ->nullabre()
                ->after('valorMax');
        });
    }

    
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('valorMax');
            $table->dropColumn('valorMin');
        });
    }
}
