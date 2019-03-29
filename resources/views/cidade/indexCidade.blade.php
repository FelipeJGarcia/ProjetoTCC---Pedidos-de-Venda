@extends('adminlte::page')

@section('content')

@if(\Input::has('success'))
    <script>
        alert("Registrado com sucesso!");
    </script>
@endif

<h1 class="title-pg">Listagem de Cidades</h1>

<!-- =============================================================================Filtro -->
<div class="box">
    <div class="box-header">
        <form action="{{ route('cidade.filtro') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="name" class="form-control" placeholder="Pesquisa pelo Nome" value="{{isset($cidade) ? $cidade : ''}}">

            <button type="submit" class="btn btn-primary btn-add">Pesquisar</button>
        </form>
    </div>
    <!-- =============================================================================Filtro -->

    <div>

        <table class="table table-striped">
                <tr>
                    <th>Nome</th>
                    <th width="100px">Ações</th>
                </tr>
            
            @foreach($cidades as $cidade)  
            <tr>
                <td>{{$cidade->name}}</td>
                <td>
                    <form name="form{{$cidade}}" action="/admin/cidades/{{$cidade->id}}" method="POST">                       
                        <a href="{{ route('cidades.edit', $cidade->id) }}" class="actions edit">  <!-- botão editar -->
                            <span class="glyphicon glyphicon-pencil"></span>   <!-- imagem do Bootstrap -->
                        </a>
                    
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="actions delete" onclick="msg()">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </form>
                    
                    @section('js')
                        <script>
                            function msg(){
                                var pergunta = confirm("Tem certeza que deseja excluir a cidade?");
                                if(pergunta){
                                    alert("Cidade excluida com sucesso!");
                                }else{
                                    alert("CANCELADO!");
                                    document.onsubmit = function(){
                                        window.location.reload();
                                        return false;
                                    };
                                }
                            }
                        </script>
                    @stop

                </td>
            </tr>
            @endforeach
            
        </table>

        @if (isset($dataForm))     <!-- paginando o uso do if e else para pegar o filtro na paginacao-->
            {!! $cidades->appends($dataForm)->links() !!}        
        @else
            {!! $cidades->links() !!}
        @endif

    </div>

</div>

<h4>Antes de cadastrar uma nova cidade. Certifique-se na listagem, se a mesma não existe.</h4>
<a href="{{ route('cidades.create') }}" class="btn btn-primary btn-add"> <span class="glyphicon glyphicon-plus"></span> Cadastrar</a> 

@endsection