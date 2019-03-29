@extends('adminlte::page')

@section('content')

@if(\Input::has('success'))
    <script>
        alert("Registrado com sucesso!");
    </script>
@endif

<h1 class="title-pg">Listagem de Produtos</h1>

<!-- =============================================================================Filtro -->
<div class="box">
    <div class="box-header">
        <form action="{{ route('produto.filtro') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="name" class="form-control" placeholder="Pesquisa pelo Nome" value="{{isset($produto) ? $produto : ''}}">

            <button type="submit" class="btn btn-primary btn-add">Pesquisar</button>
        </form>
    </div>
    <!-- =============================================================================Filtro -->

    <a href="{{ route('produtos.create') }}" class="btn btn-primary btn-add"> <span class="glyphicon glyphicon-plus"></span> Cadastrar</a> 

    <table class="table table-striped">
        <tr>
            <th>Nome</th>
            <th>Valor de Custo</th>
            <th width="100px">Ações</th>
        </tr>
        @if(isset($products))
        @foreach($products as $product)
        <tr>
            <td>{{$product->name}}</td>
            <td>{{$product->valor}}</td>
            <td>
                <a href="{{route('produtos.edit', $product->id)}}" class="actions edit">  <!-- botão editar -->
                    <span class="glyphicon glyphicon-pencil"></span>   <!-- imagem do Bootstrap -->
                </a>
                <a href="{{route('produtos.show', $product->id)}}" class="actions delete">
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

@endsection