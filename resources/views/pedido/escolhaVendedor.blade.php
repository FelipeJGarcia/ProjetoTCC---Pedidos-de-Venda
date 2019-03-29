@extends('adminlte::page')

@section('content')

<h4>Cliente Selecionado: {{$users->name}} </h4>

<form action="{{ route('pedidos.create') }}" method="get">
    <h3>
        <a href="{{ route('pedidos.index') }}"><span class="glyphicon glyphicon-fast-backward"></span></a>
         Selecione o Vendedor:
    </h3>
    <input type="hidden" name="cliente_id" value="{{$users->id}}" />
    <input type="hidden" name="status" value="1">
    <select name="colaborador_id" class="form-control">
        @foreach($vendedores as $key => $data)
            <option value="{{$data->id}}">{{$data->name}}</option>
        @endforeach
    </select>
    <hr>

    <button type="submit" class="btn btn-primary">
        Selecionar
    </button>
</form> 

@endsection