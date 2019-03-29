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

@if (Session::has('message'))
   <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

    <h1 class="title-pg">
        <a href="{{route('produtos.index')}}"><span class="glyphicon glyphicon-fast-backward"></span></a>
        Produto: <b>{{$product->name}}</b>
    </h1>

    <hr>

    <div>
    @if( count($photos) > 0 )  <!-- Mostra as imagens dentro de um for se existir -->
        @foreach( $photos as $key => $img )
        
            <img class="img" src="{{ url('/produtos/'.$img->product_id."/".$img->image)}}" width='150px' />
        
            <!--
                <a href="{{ url('/produtos/'.$img->product_id."/".$img->image)}}" title="Clique aqui para ampliar" ><img src="{{ url('/produtos/'.$img->product_id."/".$img->image)}}" width='150px' /></a>
             -->
        @endforeach
    @endif
    </div>
    
    <hr>

    <p><b>Descrição:</b> {{$product->description}}</p>
    <p><b>Valor de Custo:    </b> {{$product->valor}}</p>
    <p><b>Valor Mínimo de Venda:</b> {{$product->valorMin}}</p>
    <p><b>Valor Máximo de Venda:</b> {{$product->valorMax}}</p>

    @if( isset($errors) && count($errors) > 0 )
        <div class="alert alert-danger">
            @foreach( $errors->all() as $error )
                <p>{{$error}}</p>
            @endforeach
        </div>     
    @endif
    
    <form method="POST" action="">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="submit" value="Deletar" class="btn btn-danger" title="Deletar" onclick="msg()">
    </form>

    @section('js')
        <script>
            function msg(){ 
                var pergunta = confirm("Tem certeza que deseja excluir o cadastro?");
                if(pergunta){
                    //alert("Cadastro excluido com sucesso!");
                    return true;
                }else{
                    alert("CANCELADO!");
                    document.onsubmit = function(){
                        window.location.reload();
                        return false;
                    };
                }
            }

            $(document).ready(function(){
                $('.img').on( "click", function() {
                $(this).toggleClass('maxSize')
                });
            });
        </script>
    @stop

@endsection