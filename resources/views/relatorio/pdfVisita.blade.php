<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>PDF</title>
 
        <!--Custon CSS (está em /public/assets/site/css/certificate.css)-->
        <link rel="stylesheet" href="{{ url('assets/site/css/certificate.css') }}">
    </head>
    <body>
 
<h2><u>Relatório de Visita</u></h2> 
@if(isset($user))
    <h4>{{$user->tipo}}: <b>{{$user->name}}</b></h4>
@endif

@if($dataInicial != "" && $dataFinal != "" && $dataInicial < $dataFinal && isset($visitas))
    <h4>No pedíodo entre: <b>{{ date('d/m/Y', strtotime($dataInicial)) }}</b> a <b>{{ date('d/m/Y', strtotime($dataFinal)) }}</b></h4>
@elseif($dataInicial != "" && $dataFinal == "" && isset($visitas))
    <h4>De: <b>{{ date('d/m/Y', strtotime($dataInicial)) }}</b> em diante.</h4>
@elseif($dataFinal != "" && $dataInicial == "" && isset($visitas))
    <h4>Inferior a: <b>{{ date('d/m/Y', strtotime($dataFinal)) }}</b>.</h4>
@endif


            <table border="10" width="550">
                <tr>
                    <th>Nome do Cliente</th>
                    <th>Cidade</th>
                    <th width="100px">Data</th>
                </tr>
                @if(isset($visitas))
                <tbody>
                    @foreach($visitas as $key => $visita)
                    <tr>
                        <td>{{$visita->cliente}}</td>
                        <td>{{$visita->cidade}}</td>
                        <td>{{ date('d/m/Y', strtotime($visita->date)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                @endif 
            </table>

    </body>
</html>