<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cidade extends Model
{
    protected $fillable = [         //lista branca - colunas q podem ser preenchidas
        'name'
    ];


    public function user(){     // hasMany relacionamento de 1 para * (um para muitos)
        return $this->hasMany('App\Models\User','cidade_id');
    }

    // Metodo que recupera todas cidades ordenando pelo nome
    public static function combo()
    {
        return self::select("id","name")->orderBy("name")->get();
    }
}
