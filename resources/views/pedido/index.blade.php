@extends('adminlte::page')

@section('content')

<h1 class="title-pg">Selecione um Cliente</h1>

<!-- =============================================================================Filtro -->
<div class="box">
    <div class="box-header">
        <form action="{{ route('filtroUP.buscar') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="name" class="form-control" placeholder="Pesquisa pelo Nome">

            <button type="submit" class="btn btn-primary btn-add">Pesquisar</button>
        </form>
    </div>
    <!-- =============================================================================Filtro -->
    <table class="table table-striped">
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Tipo</th>
            <th width="170px">Ações</th>
        </tr>
        @if(isset($users))
            @foreach($users as $user)  
                 
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->cpf}}</td>
                        <td>{{$user->tipo}}</td>
                        <th>
                            <!-- Botão consultar (lupa) - chama metodo da UserController -->
                            <a href="{{route('consulta.pedidoMostraCliente', $user->id)}}" class="actions delete">
                                <span class="glyphicon glyphicon-search"></span>
                            </a>
                            <!-- Botão selecionar -->
                            <a href="{{route('seleciona.getCliente', $user->id)}}">
                               | Selecionar
                            </a> 
                        </th>
                    </tr>
                
            @endforeach
        @endif 
    </table>

    @if (isset($dataForm))       <!-- paginando o uso do if e else para pegar o filtro na paginacao-->
        {!! $users->appends($dataForm)->links() !!}        
    @else
        {!! $users->links() !!}
    @endif

</div>

@endsection