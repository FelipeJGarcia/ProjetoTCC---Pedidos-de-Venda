@extends('adminlte::page')

@section('content')

<h1 class="title-pg">
    <!-- Botão de voltar -->
    <a href="{{ route('cidades.index') }}"><span class="glyphicon glyphicon-fast-backward"></span></a>
    {{$title}}
</h1>

<!-- Mostrando as msg de erros do formulário -->
@if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-danger">
        @foreach( $errors->all() as $error )
            <p>{{$error}}</p>
        @endforeach
    </div>     
@endif

@if(isset($cidade) )
<!-- Editar cadastro -->
<form name="form1" class="form" method="post" enctype="multipart/form-data" action="{{ route('cidades.update', $cidade->id) }}"> 
        {!! method_field('PUT') !!}
@else
    <!-- Novo cadastro -->
    <form name="form1" class="form" method="post" enctype="multipart/form-data" action="{{ route('cidades.store') }}"> 
@endif

    <!-- Controle de segurança do ataque CSRF. Opção de uso 1: -->
        <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
    <!-- Controle de segurança do ataque CSRF. Opção de uso 2: -->
        {!! csrf_field() !!}

    @if(isset($cidade->id))
        <input type="hidden" name="id" value="{{$cidade->id}}"/>
    @endif

    <div class="form-group">    
        Nome:
        <input type="text" name="name" class="form-control" placeholder="Preenchimento Obrigatório" value="{{$cidade->name or old('name')}}">  
    </div>

    <button class="btn btn-primary" onclick="msg()">Registrar</button>
</form>

@section('js')
    <script>
        function msg(){
            var nomeCidade = document.form1.name.value; 

            var pergunta = confirm("Deseja continuar com o registro da cidade (" + nomeCidade + ") ?");
            if(!pergunta){
                alert("Registro CANCELADO!");
                document.forms['form1'].onsubmit = function(){   
                    window.location.reload();
                    return false;                                   
                };
            }
        }
    </script>
@stop

@endsection