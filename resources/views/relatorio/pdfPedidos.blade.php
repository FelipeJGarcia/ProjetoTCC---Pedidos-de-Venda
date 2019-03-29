<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>PDF</title>
 
        <!--Custon CSS (está em /public/assets/site/css/certificate.css)-->
        <link rel="stylesheet" href="{{ url('assets/site/css/certificate.css') }}">
    </head>
    <body>
 
<h2><u>Relatório de Pedido</u></h2> 
@if(isset($user))
    <h4>{{$user->tipo}}: <b>{{$user->name}}</b></h4>
@endif

@if($dataInicial != "" && $dataFinal != "" && $dataInicial < $dataFinal && isset($pedido))
    <h4>No pedíodo entre: <b>{{ date('d/m/Y', strtotime($dataInicial)) }}</b> a <b>{{ date('d/m/Y', strtotime($dataFinal)) }}</b></h4>
@elseif($dataInicial != "" && $dataFinal == "" && isset($pedido))
    <h4>De: <b>{{ date('d/m/Y', strtotime($dataInicial)) }}</b> em diante.</h4>
@elseif($dataFinal != "" && $dataInicial == "" && isset($pedido))
    <h4>Inferior a: <b>{{ date('d/m/Y', strtotime($dataFinal)) }}</b>.</h4>
@endif


            <table border="10" width="550">
                <tr>
                    <th>ID do Pedido</th>
                        @if(isset($user) && $user->tipo == 'Cliente')
                    <th>Vendedor</th>
                        @else
                    <th>Cliente</th>
                        @endif
                    <th>Valor</th>
                    <th width="100px">Data</th>
                </tr>
                @if(isset($pedido))
                <tbody>
                    @foreach($pedido as $key => $value)
                    <tr>
                        <td>{{$value->id}}</td>
                        @if(isset($user) && $user->tipo == 'Cliente')
                            <td>{{$value->colaborador}}</td>
                        @else
                            <td>{{$value->cliente}}</td>
                        @endif
                        <td>{{$total[$key]}}</td>
                        <td>{{ date('d/m/Y', strtotime($value->date)) }}</td>
                    </tr>    
                    @endforeach
                </tbody>
                @endif
            </table>

    </body>
</html>