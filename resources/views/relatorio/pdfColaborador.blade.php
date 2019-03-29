<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>PDF</title>
 
        <!--Custon CSS (está em /public/assets/site/css/certificate.css)-->
        <link rel="stylesheet" href="{{ url('assets/site/css/certificate.css') }}">
    </head>
    <body>
 
<h2><u>Relatório de Colaborador</u></h2>

@if($dataInicial != "" && $dataFinal != "" && $dataInicial < $dataFinal && isset($user))
    <h4>No pedíodo entre: <b>{{ date('d/m/Y', strtotime($dataInicial)) }}</b> a <b>{{ date('d/m/Y', strtotime($dataFinal)) }}</b></h4>
@elseif($dataInicial != "" && $dataFinal == "" && isset($user))
    <h4>De: <b>{{ date('d/m/Y', strtotime($dataInicial)) }}</b> em diante.</h4>
@elseif($dataFinal != "" && $dataInicial == "" && isset($user))
    <h4>Inferior a: <b>{{ date('d/m/Y', strtotime($dataFinal)) }}</b>.</h4>
@endif

            <table border="10" width="550">
                <tr>
                    <th>Nome</th>
                    <th>Total de Pedidos</th>
                    <th>Valor Total dos Pedidos</th>
                    <th>% a Receber</th>
                    <th>Lucro da Empresa</th>
                </tr>
                <tr>
                    @if(isset($user))
                        <td>{{$user->name}}</td>
                        <td>{{$pedidos}}</td>
                        <td>{{ number_format($totalDosPedidos, 2, '.', '') }}</td>
                        <td>{{ number_format(($totalDosPedidos / 100) * $user->valorPorcentagem, 2, '.', '') }}</td>
                        <td>{{ number_format($totalDosPedidos - ((($totalDosPedidos / 100) * $user->valorPorcentagem) + $totalBase), 2, '.', '') }}</td>
                    @endif
                </tr>
            </table>

    </body>
</html>