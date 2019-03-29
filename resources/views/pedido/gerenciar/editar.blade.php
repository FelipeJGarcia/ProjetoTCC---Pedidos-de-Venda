@extends('adminlte::page')

@section('content')

<h1 class="title-pg">
    <!-- Botão de voltar -->
    <a href="{{ route('pedidos.voltar', $pedido->id) }}"><span class="glyphicon glyphicon-fast-backward"></span></a>
    {{$title}}: {{$pedido->id}}
</h1>


<!-- Mostrando as msg de erros do formulário -->
@if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-danger">
        @foreach( $errors->all() as $error )
            <p>{{$error}}</p>
        @endforeach
    </div>     
@endif
<!--status: {{$pedido->status}}                                  STATUS AQUI-->                            
<p><b>Vendedor:</b> {{$pedido->colaborador}}</p>
<p><b>Cliente:</b> {{$pedido->cliente}}</p>

<form name="form1" class="form" method="post" enctype="multipart/form-data" action="{{ route('pedidos.update', $pedido->id) }}"> 
    {!! method_field('PUT') !!} 
    {!! csrf_field() !!}

    <hr style="height:2px; border:none; color:#000; background-color:#000; margin-top: 0px; margin-bottom: 0px;"/>
    <b>Itens: </b>
    <table class="table table-striped">
        <tr>
            <th>Nome</th>
            <th>Valor Un</th>
            <th>Quantidade</th>
            <th>Valor Total do Produto</th>
            <th width="100px">Ações</th>
        </tr>
        @foreach($itens as $key => $item)
            <tr>
                <td>
                    {{$item->name}}
                    <input type="hidden" name="itens[{{$key}}][id]" value="{{$item->id}}" />
                </td>
                <td><input type="number" step="0.01" name="itens[{{$key}}][valor]" class="form-control" value="{{$item->valor or old('valor')}}"></td>
                <td><input type="number" min="1" name="itens[{{$key}}][quantidade]" class="form-control" value="{{$item->quantidade or old('quantidade')}}"></td>
                <td>{{ number_format($item->valor * $item->quantidade, 2, '.', '') }}</td>
                <td>
                    <a href="{{ route('aux.deletarItemPedido', $item->id) }}" class="actions delete" title="Deletar" onclick="msgExcluirProduto()">
                        <span class="glyphicon glyphicon-trash"></span> 
                    </a>
                </td>
                <!-- ----------------------------->
            </tr>
        @endforeach
    </table>
    <p><b>Valor Total do Pedido: </b> {{$total}} </p>
    <!-- So aparece o botão de adicionar aqui quando esta editando o pedido -->
    @if($pedido->status > 1)
        <a href="{{ route('pedidos.addItemPedido', $pedido->id) }}" class="btn btn-primary btn-add"> <span class="glyphicon glyphicon-plus"></span> Adicionar Item</a>
    @endif

    <hr style="height:2px; border:none; color:#000; background-color:#000; margin-top: 0px; margin-bottom: 0px;"/>
       
    <div class="form-group">    
        Observação:
        <textarea name="observacao" class="form-control">{{$pedido->observacao or old('observacao')}}</textarea> 
    </div>
    
    <button class="btn btn-primary" onclick="msgSalvar()">Salvar</button>

    @if($pedido->status == 1)
        <a href="{{ route('pedidos.encerrarPedido', $pedido->id) }}" class="btn btn-primary btn-add" onclick="msgFinalizar()">Finalizar Pedido</a>   
    @endif
  
</form>

@section('js')
    <script>
        function msgSalvar(){
            var pergunta = confirm("Deseja salvar a alteração?");
            if(!pergunta){
                alert("Alteração CANCELADO!");
                document.forms['form1'].onsubmit = function(){   
                    window.location.reload();
                    return false;                                   
                };
            }
        }

        function msgExcluirProduto(){
            alert("Produto excluido com sucesso!");
        }

        function msgFinalizar(){
            alert("Pedido finalizado com sucesso!");
        }
    </script>
@stop

@endsection
