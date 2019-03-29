@extends('adminlte::page')

@section('content')

    <h1 class="title-pg">
        <!-- Seta que volta para o metado index da controller User -->
        <a href="{{route('user.index')}}"><span class="glyphicon glyphicon-fast-backward"></span></a>
        <!-- Titulo da pagina com nome do usuário -->
        {{$title}}  <b>{{$user->name}}</b>
    </h1>

    <!-- Inicio formulário de dados pessoais -->
    <p><b>Tipo:</b> {{$user->tipo}}</p>
    @if ($user->tipo != 'Cliente')
        <p><b>CPF: </b> {{$user->cpf}} </p> 
    @else
        <p><b>CNPJ ou CPF: </b> {{$user->cpf}} </p>
    @endif
    <p><b>Complemento:</b> {{$user->complemento}}</p>
    <p><b>Telefone 1:</b> {{$user->telefone1}}</p>
    <p><b>Telefone 2:</b> {{$user->telefone2}}</p>
    <p><b>E-mail:</b> {{$user->email}}</p>
    <!-- if no html -->
    @if ($user->tipo != 'Cliente' && auth()->user()->tipo == "Administrador") 
        <p><b>Porcentagem de Comissão: </b> {{$user->valorPorcentagem}}%</p>
    @endif
    

    <!-- Linha divisoria -->
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
    <p><b>Complemento / Endereço:</b> {{$user->complementoEnd}}</p>

    <hr>

    <!-- Botão de deletar - Aciona o metodo destroy da UserController-->
    @if(auth()->user()->tipo == "Administrador")
    <form name="del" method="POST" action="">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="submit" value="Deletar" class="btn btn-danger" title="Deletar" onclick="msg()">
    </form>
    @endif

    @section('js')
        <script>
            function msg(){ 
                var pergunta = confirm("Tem certeza que deseja excluir o cadastro?");
                if(pergunta){
                    alert("Cadastro excluido com sucesso!");
                    return true;
                }else{
                    alert("CANCELADO!");
                    document.onsubmit = function(){
                        window.location.reload();
                        return false;
                    };
                }
            }
        </script>
    @stop

@endsection