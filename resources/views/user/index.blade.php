@extends('adminlte::page')

@section('content')

@if(\Input::has('success'))
    <script>
        alert("Registrado com sucesso!");
    </script>
@endif

<h1 class="title-pg">Listagem de Pessoas</h1>

<!-- =============================================================================Filtro -->
<div class="box">
    <div class="box-header">
        <form action="{{ route('user.filtro') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="name" class="form-control" placeholder="Pesquisa pelo Nome" value="{{isset($usuario) ? $usuario : ''}}">

            <button type="submit" class="btn btn-primary btn-add">Pesquisar</button>
        </form>
    </div>
    <!-- =============================================================================Filtro -->

    <!-- (Botão cadastrar) Chama a função->cadUserEscolha que esta na controller 'Aux' -->
    <a href="{{route('escolha.cadUserEscolha')}}" class="btn btn-primary btn-add"> <span class="glyphicon glyphicon-plus"></span> Cadastrar</a> 

    <table class="table table-striped">
        <tr>
            <th>Nome</th>
            <th>CNPJ ou CPF</th>
            <th>Tipo</th>
            <th width="100px">Ações</th>
        </tr>
        @if(isset($users))
        @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->cpf}}</td>
            <td>{{$user->tipo}}</td>
            <td>                            
            <!-- botão editar -->
            <!-- Rota para o metodo->edit da controller User, envia o id do usuário -->
            @if(auth()->user()->tipo == "Administrador") 
                <a href="{{route('user.edit', $user->id)}}" class="actions edit">  
                    <span class="glyphicon glyphicon-pencil"></span>   <!-- imagem do Bootstrap -->
                </a>   
            @endif             
            <!-- Botão consultar e também de deletar (botão deletar interno) -->  
            <!-- Rota para o metodo->show da controller User, envia o id do usuário --> 
                <a href="{{route('user.show', $user->id)}}" class="actions delete">
                    <span class="glyphicon glyphicon-search"></span>    <!-- imagem do Bootstrap -->
                </a>
            </td>
        </tr>
        @endforeach
        @endif
    </table>

    <!-- paginando o uso do if e else para pegar o filtro na paginacao-->
    @if (isset($dataForm))                                    
        {!! $users->appends($dataForm)->links() !!}        
    @else
        {!! $users->links() !!}
    @endif

</div>

@endsection