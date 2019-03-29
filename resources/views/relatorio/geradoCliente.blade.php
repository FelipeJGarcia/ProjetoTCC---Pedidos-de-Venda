@extends('adminlte::page')

@section('content')

<h3><u>Relatório de Cliente</u></h3>

@if($dataInicial != "" && $dataFinal != "" && $dataInicial < $dataFinal && isset($user))
    <h4>No pedíodo entre: <b>{{ date('d/m/Y', strtotime($dataInicial)) }}</b> a <b>{{ date('d/m/Y', strtotime($dataFinal)) }}</b></h4>
@elseif($dataInicial != "" && $dataFinal == "" && isset($user))
    <h4>De: <b>{{ date('d/m/Y', strtotime($dataInicial)) }}</b> em diante.</h4>
@elseif($dataFinal != "" && $dataInicial == "" && isset($user))
    <h4>Inferior a: <b>{{ date('d/m/Y', strtotime($dataFinal)) }}</b>.</h4>
@endif

<div class="box">
    <div class="box-header">
        <table class="table table-striped">
            <tr>
                <th>Nome</th>
                <th>CNPJ ou CPF</th>
                <th>Total de Pedidos</th>
                <th>Valor Total dos Pedidos</th>
            </tr>
            <tr>
                @if(isset($user))
                    <td>{{$user->name}}</td>
                    <td>{{$user->cpf}}</td>
                    <td>{{$pedidos}}</td>
                    <td>{{ number_format($totalDosPedidos, 2, '.', '') }}</td>
                @endif
            </tr>
                
        </table>
    </div>
</div>

<a href="{{ route('relatorios.filtro', $info) }}" class="btn btn-danger">Nova Consulta</a> |
@if(isset($user))
    <a href="{{ route('relatorio.pdfCliente', $user->id) }}" class="btn btn-primary">Gerar PDF</a>
@endif
  
@endsection