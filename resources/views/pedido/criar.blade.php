@extends('adminlte::page')

@section('content')

 <!-- status: {{$pedido->status}}            STATUS AQUI-->                             
<h4>
    <b>Pedido:</b> {{$pedido->id}} | <b>Vendedor:</b> {{$vendedor}} | <b>Cliente:</b> {{$cliente}}  
</h4>

<!-- A seta de voltar só aparece se um pedido esta sendo editado -->
<h1 class="title-pg">
    @if($pedido->status > 1)
        <a href="{{ route('pedidos.edit', $pedido->id) }}"><span class="glyphicon glyphicon-fast-backward"></span></a>
    @endif
    Adicione itens ao Pedido
</h1>

<!-- =============================================================================Filtro -->
<div class="box">
    <div class="box-header">
        <form action="{{ route('filtroPP.buscar') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="name" class="form-control" placeholder="Pesquisa pelo Nome" value="{{isset($produto) ? $produto : ''}}">

            <button type="submit" class="btn btn-primary btn-add">Pesquisar</button>
        </form>
    </div>
    <!-- =============================================================================Filtro -->

    <table class="table table-striped">
        <tr>
            <th>Nome</th>
            <th width="100px">Ações</th>
        </tr>
        @if(isset($products))
        @foreach($products as $product)
        <tr>
            <td>{{$product->name}}</td>
            <td>
                <a href="{{route('add.addProduto', ['produto' => $product->id, 'pedido' => $pedido->id])}}" class="actions edit">  <!-- botão add -->
                    <span class="glyphicon glyphicon-plus"></span>   <!-- imagem do Bootstrap -->
                </a>
                <a href="{{route('consulta.pedidoMostraProduto', ['produto' => $product->id, 'pedido' => $pedido->id])}}" class="actions delete">
                    <span class="glyphicon glyphicon-search"></span>
                </a>
            </td>
        </tr>
        @endforeach
        @endif
    </table>

    @if (isset($dataForm))                                    <!-- paginando o uso do if e else para pegar o filtro na paginacao-->
        {!! $products->appends($dataForm)->links() !!}        
    @else
        {!! $products->links() !!}
    @endif

</div>

@if($pedido->status == 1)
    <a href="{{ route('pedidos.cancelarPedido', $pedido->id) }}" class="btn btn-danger" onclick="msg()">Cancelar</a>
    <!-- <a href="{{ route('aux.emManutencao') }}" class="btn btn-primary btn-add">Resgatar Pedido</a> -->
    <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-primary btn-add">Conferir Pedido</a> 
@endif

@section('js')
    <script>
        function msg(){
            alert("Pedido cancelado com sucesso!");
        }
    </script>
@stop

@endsection