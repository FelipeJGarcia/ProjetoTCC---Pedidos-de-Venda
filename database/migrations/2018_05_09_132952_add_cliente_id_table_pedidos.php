<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClienteIdTablePedidos extends Migration
{
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->integer("cliente_id")->unsigned()->index()->after('colaborador_id');
        });
    }



    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('cliente_id');
        });
    }
}
