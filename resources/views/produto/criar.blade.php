@extends('adminlte::page')

@section('content')

<h1 class="title-pg">
    <a href="{{route('produtos.index')}}"><span class="glyphicon glyphicon-fast-backward"></span></a> <!--voltar-->
    {{$title}}
</h1>

<!-- Mostrando as msg de erros do formulário -->
@if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-danger">
        @foreach( $errors->all() as $error )
            <p>{{$error}}</p>
        @endforeach
    </div>     
@endif

@if(isset($product) )
    <form name="formmain" class="form" method="post" enctype="multipart/form-data" action="{{route('produtos.update', $product->id)}}">  <!-- enviando para a controller metodo de atualizar o cadastro existente -->
        {!! method_field('PUT') !!}
@else
    <form name="formmain" class="form" method="post" enctype="multipart/form-data" action="{{route('produtos.store')}}">  <!-- enviando para a controller metodo cadastro -->
@endif

    <!-- Controle de segurança do ataque CSRF. Opção de uso 1: -->
        <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
    <!-- Controle de segurança do ataque CSRF. Opção de uso 2: -->
        {!! csrf_field() !!}

    @if(isset($product->id))
        <input type="hidden" name="id" value="{{$product->id}}"/>
    @endif
    
    <div class="form-group">                                                           <!-- se tem o produto (editar) mostra, se não é um novo cadastro fica em branco -->
        Nome:
        <input type="text" name="name" class="form-control" placeholder="Preenchimento Obrigatório. (Descrição | marcar | volume/quantidade)" value="{{$product->name or old('name')}}">  <!-- o old em value faz com q mesmo apresentando algum erro a informação ja inserida seja preservada e se mantenha -->
    </div>
        
        
    <div class="form-group">                                                           <!-- se tem o produto (editar) mostra, se não é um novo cadastro fica em branco -->
        Valor de Custo:
        <input type="text" name="valor" class="form-control" placeholder="Preenchimento Obrigatório." value="{{$product->valor or old('valor')}}">  <!-- o old em value faz com q mesmo apresentando algum erro a informação ja inserida seja preservada e se mantenha -->
    </div>

    <div class="form-group">                                                           <!-- se tem o produto (editar) mostra, se não é um novo cadastro fica em branco -->
        Valor Mínimo de Venda:
        <input type="text" name="valorMin" class="form-control" placeholder="Preenchimento Obrigatório." value="{{$product->valorMin or old('valorMin')}}">  <!-- o old em value faz com q mesmo apresentando algum erro a informação ja inserida seja preservada e se mantenha -->
    </div>

    <div class="form-group">                                                           <!-- se tem o produto (editar) mostra, se não é um novo cadastro fica em branco -->
        Valor Máximo de Venda:
        <input type="text" name="valorMax" class="form-control" placeholder="Preenchimento Obrigatório." value="{{$product->valorMax or old('valorMax')}}">  <!-- o old em value faz com q mesmo apresentando algum erro a informação ja inserida seja preservada e se mantenha -->
    </div>
   
        
    <div class="form-group">
        Descrição:
        <textarea name="description" class="form-control">{{$product->description or old('description')}}</textarea>
    </div>
        
    <div class="form-group">
        <label for="image">Imagem:</label>
        <input type="file" name="images[]" class="form-control" accept="image/png, image/jpeg" multiple />
    </div>

    <div>
   
</form>

    @if(isset($product))
        @if( count($photos) > 0 )  <!-- Mostra as imagens dentro de um for se existir -->
            <div class="row">
            @foreach( $photos as $key => $img )
                <section class="col-md-2">
                    <img src="{{ url('/produtos/'.$img->product_id."/".$img->image)}}" style="width:100%;" />
                    <hr style="margin:5px 0;" />
                    <div class="actions">
                        <form name="form{{$key}}" action="/admin/produtos/image/{{$img->id}}/{{$img->product_id}}" method="POST">
                            {!! csrf_field() !!}
                            <button class="btn btn-danger" onclick="msgExcluirImg()">
                                Excluir
                            </button>
                        </form> 
                    </div>       
                </section>    
            @endforeach
            </div>
        @endif
    @endif
    </div>
    
    <hr>

    <button class="btn btn-primary" onclick="msg()">Registrar</button>

    @section('js')
        <script>
            function msg(){
                var valorCusto = parseFloat(document.formmain.valor.value);
                var valorMin   = parseFloat(document.formmain.valorMin.value);
                var valorMax   = parseFloat(document.formmain.valorMax.value);
                //------------------------------------------
                var nomeProduto = document.formmain.name.value; 
                var pergunta = confirm("Deseja continuar com o registro do produto (" + nomeProduto + ") ?");
                if(pergunta){
                    if(valorCusto > valorMin || valorCusto > valorMax){
                        alert("O valor de custo deve ser menor que o valor mínimo e máximo de venda!");
                        document.forms['formmain'].onsubmit = function(){   
                            window.location.reload();
                            return false;                                   
                        };
                    }else if(valorMin > valorMax){
                        alert("O valor mínimo de venda não pode ser maior que o valor máximo de venda!");
                        document.forms['formmain'].onsubmit = function(){   
                            window.location.reload();
                            return false;                                   
                        };
                    }else{
                        document.formmain.submit();
                    }
                }else{
                    alert("Registro CANCELADO!");
                    document.forms['formmain'].onsubmit = function(){   
                        window.location.reload();
                        return false;                                   
                    };
                }
            }

            function msgExcluirImg(){
                alert("Imagem excluida com sucesso!");
            }
        </script>
    @stop

@endsection
