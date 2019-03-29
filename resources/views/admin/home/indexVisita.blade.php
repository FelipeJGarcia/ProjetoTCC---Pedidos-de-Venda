@extends('adminlte::page')

@section('content')

<h1 class="title-pg">Clientes para Visitar</h1>   
    <a href="{{route('conf.visita')}}" class="btn btn-primary btn-add"> <span class="glyphicon glyphicon-cog"></span> Configurar Lista</a> 

<form name="form1" action="{{ route('registra.visita') }}" method="POST" class="form form-inline">
{!! csrf_field() !!}
<div class="box">
    <table class="table table-striped">
        <tr>
            <th>Cliente</th>
            <th>Cidade</th>
            <th>Rua</th>
            <th>Numero</th>
            <th width="100px">Ações</th>
        </tr>
        @if(isset($users))
        @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->cidade}}</td>
            <td>{{$user->rua}}</td>
            <td>{{$user->numero}}</td>
            <td>            
                <input type="radio" name="selecionado" value="{{$user->id}}">
                <a href="{{ route('remove.lista', $user->id) }}" class="actions edit" onclick="msgRemover()">         
                    <span class="glyphicon glyphicon-trash"></span>     
                </a>
            </td>
        </tr>
        @endforeach
        @endif
    </table>

    <!-- paginando o uso do if e else para pegar o filtro na paginacao-->
    @if (isset($dataForm))                                    
        {!! $users->appends($dataForm)->links() !!}        
    @else
        {!! $users->links() !!}
    @endif

</div>

</form>

<button class="btn btn-primary" onclick="msg()">Registrar Visita</button>

@section('js')
    <script>
        function msg(){     
            var selecionado = $("input[name='selecionado']:checked").val();
            if(selecionado){
                alert("Visita registrada com sucesso!");
                document.form1.submit();
            }else{
                return false;
            }
        }

        function msgRemover(){
            alert("Removido da lista com sucesso!");
        }
    </script>
@stop

@endsection