@extends('adminlte::page')

@section('content')

    <h1 class="title-pg">
        <!-- Seta que volta para o metado index da controller User -->
        <a href="{{route('pedidos.voltarHome', $pedido->id)}}"><span class="glyphicon glyphicon-fast-backward"></span></a>
        <!-- Titulo da pagina com nome do usuário -->
        {{$title}}  <b>{{$pedido->id}}</b>
    </h1>
    <!-- status: {{$pedido->status}}                                  STATUS AQUI-->                           
    <p><b>Vendedor:</b> {{$pedido->colaborador}}</p>
    <p><b>Cliente: </b> {{$pedido->cliente}} </p>
    <p><b>Data: </b> {{date('d/m/Y', strtotime($pedido->date))}} </p>

    <hr style="height:2px; border:none; color:#000; background-color:#000; margin-top: 0px; margin-bottom: 0px;"/>
    <b>Itens: </b>
    <table class="table table-striped">
        <tr>
            <th>Nome</th>
            <th>Valor Un</th>
            <th>Quantidade</th>
            <th>Valor Total</th>
        </tr>
        @foreach($itens as $key => $item)
        <tr>
            <td>{{$item->name}}</td>
            <td>{{$item->valor}}</td>
            <td>{{$item->quantidade}}</td>
            <td>{{ number_format($item->valor * $item->quantidade, 2, '.', '') }}</td>
        </tr>
        @endforeach
    </table>
    <p><b>Valor Total do Pedido: </b> {{$total}} </p>
    <hr style="height:2px; border:none; color:#000; background-color:#000; margin-top: 0px; margin-bottom: 0px;"/>

    <p><b>Observação:</b> {{$pedido->observacao}}</p>

    <hr>

    <!-- Botão de deletar-->
    <form method="POST" action="">
        
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="submit" value="Deletar" class="btn btn-danger" title="Deletar"  onclick="msg()">
        
        <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-primary btn-edit"> 
        <span class="glyphicon glyphicon-pencil"></span> Editar</a>
        |
        <a href="{{ route('relatorio.pdfPedido', $pedido->id) }}" class="btn btn-primary"> 
        Gerar PDF</a>
        
        @if($pedido->status == 4)
            <a href="{{ route('aux.validarPedido', $pedido->id) }}" class="btn btn-primary" onclick="msgValidar()"> 
            Validar</a>
        @endif
    </form>

    @section('js')
        <script>
            function msg(){ 
                var pergunta = confirm("Tem certeza que deseja excluir o cadastro?");
                if(pergunta){
                    alert("Cadastro excluido com sucesso!");
                    return true;
                }else{
                    alert("CANCELADO!");
                    document.onsubmit = function(){
                        window.location.reload();
                        return false;
                    };
                }
            }

            function msgValidar(){
                alert("Validação do pedido efetuada com sucesso!");
            }
        </script>
    @stop

@endsection