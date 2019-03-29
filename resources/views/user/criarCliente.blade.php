@extends('adminlte::page')

@section('content')

<h1 class="title-pg">
    <!-- Seta que volta para o metado index da controller User -->
    <a href="{{route('user.index')}}"><span class="glyphicon glyphicon-fast-backward"></span></a>
    <!-- Se tem titudo mostra, SE NÃO mostra o padrão -->
    {{$title or 'Cadastro de Cliente'}}
</h1>

<!-- Mostrando as msg de erros do formulário -->
@if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-danger">
        @foreach( $errors->all() as $error )
            <p>{{$error}}</p>
        @endforeach
    </div>     
@endif

@if(isset($user) )  <!-- Se for uma edição ao submeter é chamado o metodo->update da controller 'User' -->
<form name="form1" class="form" method="post" enctype="multipart/form-data" action="{{route('user.update', $user->id)}}">  
        {!! method_field('PUT') !!}
@else   <!-- Se for um cadastro ao submeter é chamado o metodo->store da controller 'User' -->
    <form name="form1" class="form" method="post" enctype="multipart/form-data" action="{{route('user.store')}}">
@endif

    <!-- Controle de segurança do ataque CSRF. Opção de uso 1: -->
        <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
    <!-- Controle de segurança do ataque CSRF. Opção de uso 2: -->
        {!! csrf_field() !!}
    
    <!-- Inicio formulário pessoal -->
    @if(isset($user->id))
        <input type="hidden" name="id" value="{{$user->id}}"/>
    @endif
    <input type="hidden" name="tipo" VALUE="Cliente">  
        
    <div class="form-group">    
        Nome:
        <input type="text" name="name" class="form-control" placeholder="Preenchimento Obrigatório" value="{{$user->name or old('name')}}">  
    </div>
     
        
    <div class="form-group">
        CNPJ ou CPF:
        <input type="text" name="cpf" class="form-control" placeholder="Preenchimento Obrigatório" value="{{$user->cpf or old('cpf')}}">
    </div>
        
        
    <div class="form-group">
        Complemento:
        <textarea name="complemento" class="form-control">{{$user->complemento or old('complemento')}}</textarea>
    </div>
    
        
    <div class="form-group">
        Telefone 1:
        <input type="text" name="telefone1" class="form-control" placeholder="Preenchimento Obrigatório" value="{{$user->telefone1 or old('telefone1')}}">
    </div>
        
        
    <div class="form-group">
        Telefone 2:
        <input type="text" name="telefone2" class="form-control" value="{{$user->telefone2 or old('telefone2')}}">
    </div>
        
        
    <div class="form-group">
        E-mail:
        <input type="text" name="email" class="form-control" value="{{$user->email or old('email')}}">
    </div>

    <hr style="height:2px; border:none; color:#000; background-color:#000; margin-top: 0px; margin-bottom: 0px;"/>

    <!-- Inicio formulário de endereço -->
    <hr>
    <div class="form-group">
        Cidade:
        <select name="cidade_id" class="form-control">
                @foreach($cidades as $key => $cidade)
                    <option value="{{$cidade->id}}"
                        @if(isset($user) && $user->cidade_id == $cidade->id)   
                            selected
                        @endif
                        >{{$cidade->name}}</option>
                @endforeach
        </select>
    </div>

    <div class="form-group">
        CEP:
        <input type="text" name="cep" class="form-control" value="{{$user->cep or old('cep')}}">
    </div>

    <div class="form-group">
        Bairro:
        <input type="text" name="bairro" class="form-control" placeholder="Preenchimento Obrigatório" value="{{$user->bairro or old('bairro')}}">
    </div>

    <div class="form-group">
        Rua:
        <input type="text" name="rua" class="form-control" placeholder="Preenchimento Obrigatório" value="{{$user->rua or old('rua')}}">
    </div>

    <div class="form-group">
        Numero:
        <input type="text" name="numero" class="form-control" placeholder="Preenchimento Obrigatório" value="{{$user->numero or old('numero')}}">
    </div>

    <div class="form-group">
        Complemento / Endereço:
        <textarea name="complementoEnd" class="form-control" placeholder="Caso a cidade não esteja disponível para seleção, favor informar aqui.">{{$user->complementoEnd or old('complementoEnd')}}</textarea>
    </div>
       
    <button class="btn btn-primary" onclick="msg()">Registrar</button>
</form>

@section('js')
    <script>
        function msg(){
            var nomeCliente = document.form1.name.value; 

            var pergunta = confirm("Deseja continuar com o registro do cliente (" + nomeCliente + ") ?");
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