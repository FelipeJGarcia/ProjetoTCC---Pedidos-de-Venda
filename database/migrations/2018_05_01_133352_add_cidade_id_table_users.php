<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCidadeIdTableUsers extends Migration
{
   
    public function up()
    {
        Schema::table('users', function (Blueprint $table) 
        {
            $table->integer("cidade_id")->unsigned()->index()->after('password');
        });
    }

    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cidade_id');
        });
    }
}
