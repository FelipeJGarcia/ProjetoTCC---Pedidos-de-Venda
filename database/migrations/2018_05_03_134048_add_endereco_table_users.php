<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnderecoTableUsers extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cep')->nullabre()->after('cidade_id');
            $table->string('bairro')->after('cep');
            $table->string('rua')->after('bairro');
            $table->integer('numero')->nullabre()->after('rua');
            $table->text('complementoEnd')->nullabre()->after('numero');
        });
    }


    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cep');
            $table->dropColumn('bairro');
            $table->dropColumn('rua');
            $table->dropColumn('numero');
            $table->dropColumn('complementoEnd');
        });
    }
}
