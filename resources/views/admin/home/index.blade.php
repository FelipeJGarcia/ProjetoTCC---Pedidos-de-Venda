@extends('adminlte::page')

@section('content')

<h1 class="title-pg">Listagem de Novos Pedidos</h1>
<div class="box">
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
            <th>
                <a href="{{route('aux.conferiPedido', $pedido->id)}}" class="actions delete">Conferir</a>
            </th>
        </tr>
        @endforeach
        @endif
    </table>

    @if (isset($dataForm))                <!-- paginando o uso do if e else para pegar o filtro na paginacao-->
        {!! $pedidos->appends($dataForm)->links() !!}        
    @else
        {!! $pedidos->links() !!}
    @endif

</div>

<a href="{{ route('aux.index') }}" class="btn btn-primary btn-update"> 
<span class="glyphicon glyphicon-refresh"></span> Atualizar</a>

<!----------------- temporário, acesso a home do vendedor -->
<a href="{{ route('home.vendedor') }}" class="btn btn-primary btn-update">Home de Vendedor</a>

@endsection