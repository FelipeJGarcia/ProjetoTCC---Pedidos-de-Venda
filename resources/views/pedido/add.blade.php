@extends('adminlte::page')

@section('content')

<h1 class="title-pg">
    <a href="{{route('pedido.produtosListaAdd')}}"><span class="glyphicon glyphicon-fast-backward"></span></a> <!--voltar-->
    {{$title}}: {{$product->name}}
</h1>

<!-- Mostrando as msg de erros do formulário -->
@if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-danger">
        @foreach( $errors->all() as $error )
            <p>{{$error}}</p>
        @endforeach
    </div>     
@endif


<form class="form" method="post" action="{{route('pedido.createItem')}}">  
    
    <input type="hidden" name="produto_id" value="{{$produto_id}}" />
    <!-- Controle de segurança do ataque CSRF. Opção de uso 1: -->
        <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
    <!-- Controle de segurança do ataque CSRF. Opção de uso 2: -->
        {!! csrf_field() !!}

    <p><b>Valor Mínimo de Venda:</b> {{$product->valorMin}}</p>
    <p><b>Valor Máximo de Venda:</b> {{$product->valorMax}}</p>

    <input type="hidden" name="id" value="{{$pedido_id}}"/>
    <input type="hidden" name="produto" value="{{$product->id}}"/>

    <div class="form-group">                                                           <!-- se tem o produto (editar) mostra, se não é um novo cadastro fica em branco -->
        Insira o Valor:
        <input type="number" step="0.01" name="valor" class="form-control" value="{{old('valor')}}" required>  <!-- o old em value faz com q mesmo apresentando algum erro a informação ja inserida seja preservada e se mantenha -->
    </div>
        
        
    <div class="form-group">                                                           <!-- se tem o produto (editar) mostra, se não é um novo cadastro fica em branco -->
        Quantidade:
        <input type="number" min="1" name="quantidade" class="form-control" value="{{old('valor')}}" required>  <!-- o old em value faz com q mesmo apresentando algum erro a informação ja inserida seja preservada e se mantenha -->
    </div>
    
    <hr>

    <button class="btn btn-primary">Adicionar</button> 
</form>
    
    

@endsection