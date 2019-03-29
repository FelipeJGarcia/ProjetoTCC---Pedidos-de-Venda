@extends('adminlte::page')

@section('content')

<h3><u>Relatório de Pedido</u></h3> 
@if(isset($user))
    <h4>{{$user->tipo}}: <b>{{$user->name}}</b></h4>
@endif

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
                <th>ID do Pedido</th>
                @if(isset($user) && $user->tipo == 'Cliente')
                    <th>Vendedor</th>
                @else
                    <th>Cliente</th>
                @endif
                <th>Valor</th>
                <th width="100px">Data</th>
            </tr>
            <tr>
               @if(isset($ped))
                    @foreach($ped as $key => $pedido)
                    <tr>
                        <td>{{$pedido->id}}</td>
                        @if(isset($user) && $user->tipo == 'Cliente')
                            <td>{{$pedido->colaborador}}</td>
                        @else
                            <td>{{$pedido->cliente}}</td>
                        @endif
                        <td>{{$total[$key]}}</td>
                        <td>{{ date('d/m/Y', strtotime($pedido->date)) }}</td>
                    </tr>
                    @endforeach
                @endif
            </tr>  
        </table>   
@if(isset($ped)) 
    {!! $ped->links() !!}
@endif
    </div>
</div>

<a href="{{ route('relatorios.filtro', $info) }}" class="btn btn-danger">Nova Consulta</a> |
@if(isset($ped))
    <a href="{{ route('relatorio.pdfPedidos', $user->id) }}" class="btn btn-primary">Gerar PDF</a>
@endif
  
@endsection