@extends('adminlte::page')

@section('content')

<h1>
    <a href="{{ route('relatorios.index') }}"><span class="glyphicon glyphicon-fast-backward"></span></a>
    Relatório de {{$info}}
</h1>

<div class="box">
    <div class="box-header">
        <form name="form1" action="{{ route('relatorios.filtroNome', $info) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            @if($info == 'visita' or $info == 'colaborador' or $info == 'pedido')
                <input type="text" name="colaborador" class="form-control" placeholder="Pesquisa pelo Vendedor" value="{{isset($colaborador) ? $colaborador : ''}}">
                |
                @if($info == 'pedido')
                    <input type="text" name="cliente" class="form-control" placeholder="Pesquisa pelo Cliente" value="{{isset($cliente) ? $cliente : ''}}">
                    |
                @endif   
            @else
                <input type="text" name="cliente" class="form-control" placeholder="Pesquisa pelo Cliente" value="{{isset($cliente) ? $cliente : ''}}">
                |
            @endif

            <button class="btn btn-primary" onClick="document.form1.submit()">Pesquisar</button>
        </form>


        <form name="form2" action="{{ route('relatorios.gerado', $info) }}" method="POST" class="form form-inline">
        {!! csrf_field() !!}
            <hr>
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
                                <input type="radio" name="selecionado" value="{{$user->id}}">
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
    </div>

            <!-- paginando o uso do if e else para pegar o filtro na paginacao-->
            @if (isset($dataForm))                                    
                {!! $users->appends($dataForm)->links() !!}        
            @else
                {!! $users->links() !!}
            @endif

            <br>

            Data inicial: <input type="date" name="dateInicial" class="form-control-inline" value="{{isset($dateInicial) ? $dateInicial : ''}}">
            Data final: <input type="date" name="dateFinal" class="form-control-inline" value="{{isset($dateFinal) ? $dateFinal : ''}}">
        </form>

</div>

<button class="btn btn-primary" onClick="document.form2.submit()">Gerar Relatório</button>
  
@endsection