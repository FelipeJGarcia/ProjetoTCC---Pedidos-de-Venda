@extends('adminlte::page')

@section('content')

    <h1 class="title-pg">
        <a href="{{route('pedidos.index')}}"><span class="glyphicon glyphicon-fast-backward"></span></a> <!--voltar-->
        {{$title}}  <b>{{$user->name}}</b>
    </h1>
    <p><b>Tipo:</b> {{$user->tipo}}</p>
    <p><b>CPF: </b> {{$user->cpf}} </p>
    <p><b>Complemento:</b> {{$user->complemento}}</p>
    <p><b>Telefone 1:</b> {{$user->telefone1}}</p>
    <p><b>Telefone 2:</b> {{$user->telefone2}}</p>
    <p><b>E-mail:</b> {{$user->email}}</p>

    <hr style="height:2px; border:none; color:#000; background-color:#000; margin-top: 0px; margin-bottom: 0px;"/>

    <!-- Inicio formulário de endereço -->
    <hr>
    @foreach($cidades as $key => $cidade)
        @if($user->cidade_id == $cidade->id)   
            <p><b>Cidade:</b> {{$cidade->name}}</p> 
        @endif
    @endforeach
    <p><b>CEP:</b> {{$user->cep}}</p>
    <p><b>Bairro:</b> {{$user->bairro}}</p>
    <p><b>Rua:</b> {{$user->rua}}</p>
    <p><b>Numero:</b> {{$user->numero}}</p>
    <p><b>Complemento / Cidade:</b> {{$user->complementoEnd}}</p>

    <a href="{{route('seleciona.getCliente', $user->id)}}" class="btn btn-primary btn-add">
        Selecionar
    </a>

@endsection