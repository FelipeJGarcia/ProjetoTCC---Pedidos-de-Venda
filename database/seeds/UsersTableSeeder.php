<?php

use Illuminate\Database\Seeder;
use App\User;                   // comando inserido

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        User::create([
           'name'             => 'admin',
           'tipo'             => 'Administrador',
           'name'             => 'Administrador',
           'cpf'              => '000',
           'complemento'      => 'complemento',
           'telefone1'        => 'fone1',
           'telefone2'        => 'fone2',
           'email'            => 'megalimpeza@mega.com.br',
           'password'         => bcrypt('123456'),
           'valorPorcentagem' => '1',
           'cidade_id'        => '2',
           'cep'              => '000',
           'bairro'           => 'bairro',
           'rua'              => 'rua',
           'numero'           => '000',
           'complementoEnd'   => 'compleE',
           'visita_id'        => '0',
        ]);
    }
}
