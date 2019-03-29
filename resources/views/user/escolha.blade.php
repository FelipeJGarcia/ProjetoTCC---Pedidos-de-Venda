@extends('adminlte::page')

@section('content')

<h1 class="title-pg">
    <a href="{{route('user.index')}}"><span class="glyphicon glyphicon-fast-backward"></span></a>
    {{$title}}
</h1>

<!-- Envia via GET para controller Aux -> função'formView' o tipo escolhido -->
<form class="form" method="get" action="/admin/aux/view">
    <div class="form-group">
        <select name="tipo" class="form-control">
        	@if(auth()->user()->tipo == "Administrador")
                @foreach($tipo as $tipo)
                    <option value="{{$tipo}}">{{$tipo}}</option>
                @endforeach
            @else
            	<option value="Cliente">Cliente</option>
            @endif
        </select>
    </div>
    
    <button class="btn btn-primary">Seguir</button>

</form>

@endsection