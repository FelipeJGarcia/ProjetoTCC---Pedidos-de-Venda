@extends('adminlte::page')

@section('content')

@if(\Input::has('success'))
    <script>
        alert("Salvo com sucesso!");
    </script>
@endif

<h1 class="title-pg">Listagem de Pedidos</h1>

<!-- =============================================================================Filtro -->
<div class="box">
    <div class="box-header">
        <form action="{{ route('pedido.filtros') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="Pesquisa pelo ID" value="{{isset($id) ? $id : ''}}">
            |
            <input type="text" name="colaborador" class="form-control" placeholder="Pesquisa pelo Vendedor" value="{{isset($colaborador) ? $colaborador : ''}}">
            |
            <input type="text" name="cliente" class="form-control" placeholder="Pesquisa pelo Cliente" value="{{isset($cliente) ? $cliente : ''}}">
            |
            <input type="date" name="date" class="form-control" placeholder="Pesquisa pela Data" value="{{isset($data) ? $data : ''}}">
            <button type="submit" class="btn btn-primary btn-add">Pesquisar</button>
        </form>
    </div>
    <!-- =============================================================================Filtro --> 

    <table class="table table-striped">
        <tr>
            <th>ID do Pedido</th>
            <th>Vendedor</th>
            <th>Cliente</th>
            <th>Data</th>
            <th width="100px">Ações</th>
        </tr>
        @if(isset($pedidos))
        @foreach($pedidos as $pedido)
        <tr>
            <td>{{$pedido->id}}</td>
            <td>{{$pedido->colaborador}}</td>
            <td>{{$pedido->cliente}}</td>
            <td>{{ date('d/m/Y', strtotime($pedido->date)) }}</td>
            <td>
                <a href="{{route('pedidos.show', $pedido->id)}}" class="actions delete">
                    <span class="glyphicon glyphicon-search"></span>
                </a>
            </td>
        </tr>
        @endforeach
        @endif
    </table>

      @if (isset($dataForm))                                    <!-- paginando o uso do if e else para pegar o filtro na paginacao-->
            {!! $pedidos->appends($dataForm)->links() !!}        
      @else
            {!! $pedidos->links() !!}
      @endif

</div>

@endsection