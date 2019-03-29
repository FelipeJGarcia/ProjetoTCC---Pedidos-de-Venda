@extends('adminlte::page')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<style type="text/css">
    .maxSize {
    height: 30%;
    width: 30%;
}

    img {
        border-radius: 10px;
        border-width: medium;
        border-style: solid;
        border-color: #17B03B;
    }

</style>

    <h1 class="title-pg">
        <a href="{{route('pedido.produtosListaAdd')}}"><span class="glyphicon glyphicon-fast-backward"></span></a>
        Produto: <b>{{$product->name}}</b>
    </h1>

    <hr>

    <div>
    @if( count($photos) > 0 )  <!-- Mostra as imagens dentro de um for se existir -->
        @foreach( $photos as $key => $img )
            <img class="img" src="{{ url('/produtos/'.$img->product_id."/".$img->image)}}" width='150px' />
        @endforeach
    @endif
    </div>
    
    <hr>

    <p><b>Descrição:</b> {{$product->description}}</p>
    
      <a href="{{route('add.addProduto', ['produto' => $product->id, 'pedido' => $pedido->id])}}" class="actions edit">  <!-- botão add -->
        <span class="glyphicon glyphicon-plus"></span>   <!-- imagem do Bootstrap -->
      </a>

    @if( isset($errors) && count($errors) > 0 )
        <div class="alert alert-danger">
            @foreach( $errors->all() as $error )
                <p>{{$error}}</p>
            @endforeach
        </div>     
    @endif


    @section('js')
        <script>
            $(document).ready(function(){
                $('.img').on( "click", function() {
                $(this).toggleClass('maxSize')
                });
            });
        </script>
    @stop


@endsection