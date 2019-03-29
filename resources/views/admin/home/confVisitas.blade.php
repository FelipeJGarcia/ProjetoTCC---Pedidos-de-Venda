@extends('adminlte::page')

@section('content')

<h1 class="title-pg">
    <a href="{{route('home.vendedor')}}"><span class="glyphicon glyphicon-fast-backward"></span></a>
    Adicione Clientes a Lista de Visita
</h1>   

<!-- Filtro ----------------------------------------->
    <div class="form-group">
        Filtro por Cidade:
        <form name="form1" action="{{ route('filtro.confCid') }}" method="POST" class="form form-inline">
        {!! csrf_field() !!}
            <select name="cidade_id" class="form-control">
                    @foreach($cidades as $key => $cid)
                        <option value="{{$cid->id}}" 
                            @if(isset($cidade)) 
                                @if($cidade == $cid->id)
                                    selected 
                                @endif
                            @endif>

                            @if($cid->id == 4)   
                                {{$cid->name = '> Todas'}}
                            @else
                                {{$cid->name}}
                            @endif
                        </option>
                    @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-add" onClick="document.form1.submit()">Buscar</button>
        </form>
        
    </div>
<!-- -------------------------------------------------->
<form name="form2" action="{{ route('add.lista') }}" method="POST" class="form form-inline">
{!! csrf_field() !!}
<div class="box">
    <table class="table table-striped">
        <tr>
            <th>Cliente</th>
            <th>CNPJ ou CPF</th>
            <th>Cidade</th>
            <th>Ultima Visita</th>
            <th width="100px">Ações</th>
        </tr>
        @if(isset($users))
        @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->cpf}}</td>
            <td>{{$user->cid}}</td>
            <td>{{$user->ultima_visita}}</td>
            <td>                            
                <input type="radio" name="selecionado" value="{{$user->id}}">
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

<button class="btn btn-primary" onclick="msg()">Adicionar a Lista</button>

@section('js')
    <script>
        function msg(){
            var selecionado = $("input[name='selecionado']:checked").val();
            if(selecionado){
                alert("O cliente foi adicionado na lista com sucesso!");
                document.form2.submit();
            }else{
                return false;
            }
        }
    </script>
@stop

@endsection