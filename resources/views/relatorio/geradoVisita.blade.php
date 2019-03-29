@extends('adminlte::page')

@section('content')

<h3><u>Relatório de Visita</u></h3> 
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

<div class="box">
    <div class="box-header">
        <table class="table table-striped">
            <tr>
                <th>Nome do Cliente</th>
                <th>Cidade</th>
                <th width="100px">Ultima Visita</th>
            </tr>
            <tr>
                @if(isset($visitas))
                    @foreach($visitas as $key => $visita)
                    <tr>
                        <td>{{$visita->cliente}}</td>
                        <td>{{$visita->cidade}}</td>
                        <td>{{ date('d/m/Y', strtotime($visita->date)) }}</td>
                    </tr>
                    @endforeach
                @endif
            </tr>
                
        </table>
        @if(isset($visitas)) 
            {!! $visitas->links() !!}
        @endif
    </div>
</div>

<a href="{{ route('relatorios.filtro', 'visita') }}" class="btn btn-danger">Nova Consulta</a> |
@if(isset($visitas))
    <a href="{{ route('relatorio.pdfVisita', $user->id) }}" class="btn btn-primary">Gerar PDF</a>
@endif
  
@endsection