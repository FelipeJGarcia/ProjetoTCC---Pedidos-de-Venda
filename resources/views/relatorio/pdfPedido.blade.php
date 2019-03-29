<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>PDF</title>
 
        <!--Custon CSS (está em /public/assets/site/css/certificate.css)-->
        <link rel="stylesheet" href="{{ url('assets/site/css/certificate.css') }}">
    </head>
    <body>
 
<h1>Relatório do Pedido: {{$pedido->id}}</h1>
    
    <hr style="height:2px; border:none; color:#000; background-color:#000; margin-top: 0px; margin-bottom: 0px;"/>
                               
    <p><b>Vendedor:</b> {{$pedido->colaborador}}</p>
    <p><b>Cliente: </b> {{$pedido->cliente}} </p>
    <p><b>Data: </b> {{date('d/m/Y', strtotime($pedido->date))}} </p>

    <hr style="height:2px; border:none; color:#000; background-color:#000; margin-top: 0px; margin-bottom: 0px;"/>
    <b>Itens: </b>
    <table border="10" width="550">
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

    </body>
</html>