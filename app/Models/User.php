<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class User extends Model
{
    protected $fillable = [         //lista branca - colunas q podem ser preenchidas
        'name', 'tipo', 'cpf', 'complemento', 'telefone1', 'telefone2', 'email', 'password', 'valorPorcentagem', 'cidade_id', 'cep', 'bairro', 'rua', 'numero', 'complementoEnd', 'visita_id'
    ]; 
    //protected $quarded = []; //lista negra - colunas q não podem ser preenchidas

    
    public function cidade(){
        return $this->belongsTo('App\Models\Cidade','cidade_id');
    }
    public function pedidoCliente(){
        return $this->hasMany('App\Models\Pedido','cliente_id');
    }
    public function pedidoColaborador(){
        return $this->hasMany('App\Models\Pedido','colaborador_id');
    }

    public function search(Array $data, $totalPorPagina)  // filtro
    {
        $dbug = $this->where(function($query) use ($data)
        {
            if (isset($data['name']))
            {   
                $name = $data['name'];
                $query->whereRaw("name LIKE '%$name%'");  // comparação com apenas parte do nome
            } 
        })//->toSql(); dd($dbug);  //dbug da query q vai ser rodada
        ->paginate($totalPorPagina);
        return $dbug;  
    }

    //Retorna o nome do Usuário
    public static function getNameById($id){

        $user = self::select("name")->where("id",$id);

        if($user->count() == 0){
            return "";
        }else{
            return $user->get()[0]->name;
        }
    }

}
