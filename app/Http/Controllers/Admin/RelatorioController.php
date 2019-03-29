<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visita;
use App\Models\Pedido;
use App\Models\Product;
use App\Http\Requests\UserFormRequest;  
use Carbon\Carbon;
use PDF;
use App;

class RelatorioController extends Controller
{

    private $user; 
    private $visita;  
    private $pedido;
    private $totalPorPagina = 6;
 
    public function __construct(User $user, Pedido $pedido, Visita $visita) 
    { 
        $this->user = $user;
        $this->pedido = $pedido;
        $this->visita = $visita;
    }
    
    public function index()
    {
        $clientes = \DB::table('users')->count();
        $vendedor = \DB::table('users')->groupBy('tipo')->where('tipo','vendedor')->count();
        $admin = \DB::table('users')->groupBy('tipo')->where('tipo','administrador')->count();
        $colaboradores = $vendedor + $admin;

        $anoMes = date('Y-m');
        $dataInicio = date($anoMes.'-01');
        //$dataAtual = date('Y-m-d');       data atual não é necessário neste caso

        $mesPedidos = \DB::table('pedidos')->whereDate('date', '>=', $dataInicio)->count();
        $mesVisitas = \DB::table('visitas')->whereDate('date', '>=', $dataInicio)->count();

        return view('relatorio/index', compact('clientes', 'colaboradores', 'mesPedidos', 'mesVisitas'));
    }

    
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }

    public function visitaPDF($id)
    {
        //echo "to em visitaPDF {$id}";

        $usuario     = session()->get("formUsuario","");
        $dataInicial = session()->get("formDataInicial","");
        $dataFinal   = session()->get("formDataFinal","");

        $user = $this->user->find($id);

        if($dataInicial != "" && $dataFinal != "")
            {
                $visitas = $this->visita->selectRaw("visitas.id, date, colaborador_id, cliente_id, c.name as cliente, cid.name as cidade")
                    ->join("users as c","c.id","=","cliente_id")
                    ->join("cidades as cid","cid.id","=","c.cidade_id")
                    ->where('colaborador_id', '=', $user->id)
                    ->WhereDate('date', '>=', $dataInicial)
                    ->WhereDate('date', '<=', $dataFinal)
                    ->orderBy("date", "desc")
                    ->paginate($this->totalPorPagina);
               
            }elseif($dataFinal != ""){
                $visitas = $this->visita->selectRaw("visitas.id, date, colaborador_id, cliente_id, c.name as cliente, cid.name as cidade")
                    ->join("users as c","c.id","=","cliente_id")
                    ->join("cidades as cid","cid.id","=","c.cidade_id")
                    ->where('colaborador_id', '=', $user->id)
                    ->WhereDate('date', '<=', $dataFinal)
                    ->orderBy("date", "desc")
                    ->paginate($this->totalPorPagina);

            }elseif($dataInicial != ""){
                $visitas = $this->visita->selectRaw("visitas.id, date, colaborador_id, cliente_id, c.name as cliente, cid.name as cidade")
                    ->join("users as c","c.id","=","cliente_id")
                    ->join("cidades as cid","cid.id","=","c.cidade_id")
                    ->where('colaborador_id', '=', $user->id)
                    ->WhereDate('date', '>=', $dataInicial)
                    ->orderBy("date", "desc")
                    ->paginate($this->totalPorPagina);

            }else{
                $visitas = $this->visita->selectRaw("visitas.id, date, colaborador_id, cliente_id, c.name as cliente, cid.name as cidade")
                    ->join("users as c","c.id","=","cliente_id")
                    ->join("cidades as cid","cid.id","=","c.cidade_id")
                    ->where('colaborador_id', '=', $user->id)
                    ->orderBy("date", "desc")
                    ->paginate($this->totalPorPagina);
            }


        session()->put("formUsuario",$usuario);
        session()->put("formDataInicial",$dataInicial);
        session()->put("formDataFinal",$dataFinal);

        return \PDF::loadView('relatorio.pdfVisita', compact('user', 'visitas', 'dataInicial', 'dataFinal'))
            // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
            ->download('Relatório de Visita do Vendedor - '. $user->name .'.pdf');

    }


    public function pedidoPDF($id)
    {
        $title = 'Pedido:';

        $pedido = $this->pedido->selectRaw("pedidos.id, status, date, observacao, c.name as cliente, v.name as colaborador")
                   ->join("users as c","c.id","=","cliente_id") 
                   ->join("users as v","v.id","=","colaborador_id")
                   ->where("pedidos.id", "=", $id)->get()[0];

        $itens = $this->pedido->itensPedido($id);

        $total = $this->pedido->total($id);
      
        //return view('relatorio/pdfPedido', compact('title', 'pedido', 'itens', 'total'));
        return \PDF::loadView('relatorio.pdfPedido', compact('title', 'pedido', 'itens', 'total'))
            // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
            ->download('Relatório do Pedido - '. $pedido->id .'.pdf');
    }


    public function pedidosPDF()
    {   
        $usuario     = session()->get("formUsuario",null);
        $dataInicial = session()->get("formDataInicial",null);
        $dataFinal   = session()->get("formDataFinal",null);
        $where       = "pedidos.id > 0";

        $user = $this->user->find($usuario);

        if(!is_null($usuario)){
            if($user->tipo == "Cliente"){
                $where .= " and cliente_id = {$usuario} ";
            }else{
                $where .= " and colaborador_id = {$usuario} ";
            }
            
        }
        if(!is_null($dataInicial) and !empty($dataInicial)){
            $where .= " and date >= '{$dataInicial}' ";
        }
        if(!is_null($dataFinal) and !empty($dataFinal)){
            $where .= " and date <= '{$dataFinal}' ";
        }
        
        $select = "pedidos.id,pedidos.status,pedidos.date,pedidos.observacao,c.name as cliente,v.name as colaborador";
        
        $pedido   = $this->pedido
        ->selectRaw($select)
        ->join("users as c","c.id","=","cliente_id") 
        ->join("users as v","v.id","=","colaborador_id") 
        ->whereRaw($where)
        ->get();

        if(isset($pedido)){
            foreach ($pedido as $key => $valor){         
                $total[$key] = $this->pedido->total($valor->id);
            }    
        }
        //$user = $this->user->find($usuario);       
        //return view('relatorio/pdfPedidos', compact('pedido', 'dataInicial', 'dataFinal', 'total'));
        return \PDF::loadView('relatorio.pdfPedidos', compact('pedido', 'dataFinal', 'dataInicial', 'total', 'user'))
            // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
            ->download('Relatório de Pedidos - '. $user->name .'.pdf');
    }


    public function colaboradorPDF($id)
    {
        $usuario     = session()->get("formUsuario","");
        $dataInicial = session()->get("formDataInicial","");
        $dataFinal   = session()->get("formDataFinal","");

        $user = $this->user->find($id);

        if($dataInicial != "" && $dataFinal != "")
        {
            $pedidos = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
            ->WhereDate('date', '>=', $dataInicial)
            ->WhereDate('date', '<=', $dataFinal)
            ->count();

            $ids = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
            ->WhereDate('date', '>=', $dataInicial)
            ->WhereDate('date', '<=', $dataFinal)
            ->get();
               
        }elseif($dataFinal != ""){
            $pedidos = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
            ->WhereDate('date', '<=', $dataFinal)
            ->count();

            $ids = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
            ->WhereDate('date', '<=', $dataFinal)
            ->get();

        }elseif($dataInicial != ""){
            $pedidos = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
            ->WhereDate('date', '>=', $dataInicial)
            ->count();
                
            $ids = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
            ->WhereDate('date', '>=', $dataInicial)
            ->get();

        }else{
            $pedidos = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)->count();

            $ids = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)->get();
        }

        //==> Global Interno (Colaborador)
        $totalDosPedidos = 0;
        $totalBase = 0;
        foreach ($ids as $key => $valor) 
        {         
            $total = $this->pedido->total($valor->id);
            $totalDosPedidos = $totalDosPedidos + $total;

            $totalB = $this->pedido->totalBase($valor->id);
            $totalBase = $totalBase + $totalB;
        }

        session()->put("formUsuario",$usuario);
        session()->put("formDataInicial",$dataInicial);
        session()->put("formDataFinal",$dataFinal);

        return \PDF::loadView('relatorio.pdfColaborador', compact('user', 'pedidos', 'totalDosPedidos', 'dataInicial', 'dataFinal', 'totalBase'))
            // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
            ->download('Relatório do Colaborador - '. $user->name .'.pdf');
    }


    public function clientePDF($id)
    { 
        $usuario     = session()->get("formUsuario","");
        $dataInicial = session()->get("formDataInicial","");
        $dataFinal   = session()->get("formDataFinal","");

        $user = $this->user->find($id);

        if($dataInicial != "" && $dataFinal != "")
            {
                $pedidos = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->WhereDate('date', '<=', $dataFinal)
                ->count();

                $ids = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->WhereDate('date', '<=', $dataFinal)
                ->get();

            }elseif($dataFinal != ""){
                $pedidos = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '<=', $dataFinal)
                ->count();

                $ids = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '<=', $dataFinal)
                ->get();
            }elseif($dataInicial != ""){
                $pedidos = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->count();
                
                $ids = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->get();
            }else{
                $pedidos = \DB::table('pedidos')->where('cliente_id', '=', $user->id)->count();

                $ids = \DB::table('pedidos')->where('cliente_id', '=', $user->id)->get();
            }

            //==> Global Interno (Cliente)
            $totalDosPedidos = 0;
            foreach ($ids as $key => $valor) 
            {         
                $total = $this->pedido->total($valor->id);
                $totalDosPedidos = $totalDosPedidos + $total;
            }

        session()->put("formUsuario",$usuario);
        session()->put("formDataInicial",$dataInicial);
        session()->put("formDataFinal",$dataFinal);
        
        return \PDF::loadView('relatorio.pdfCliente', compact('user', 'pedidos', 'totalDosPedidos', 'dataInicial', 'dataFinal'))
            // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
            ->download('Relatório do Cliente - '. $user->name .'.pdf');
    }


    public function filtro(Request $request, User $user, $info)
    {
        $dataForm = $request->except('_token');

        $cliente     = isset($dataForm["cliente"])     ? $dataForm["cliente"]     : "";
        $colaborador = isset($dataForm["colaborador"]) ? $dataForm["colaborador"] : "";
        $where   = "users.id > 0";

        if(!empty($cliente)){
            $where = "name LIKE '%{$cliente}%'";
        }
        if(!empty($colaborador)){
            $where = "name LIKE '%{$colaborador}%'";
        }

        if($info == 'colaborador')
        {
            $users = $this->user->selectRaw("users.id, name, cpf, tipo")
            ->whereRaw($where)
            ->where("tipo", "!=", "Cliente")
            ->orderBy("id", "desc")
            ->paginate($this->totalPorPagina);
        }elseif($info == 'pedido'){
            if(!empty($colaborador)){
                $users = $this->user->selectRaw("users.id, name, cpf, tipo")
                ->whereRaw($where)
                ->where("tipo", "!=", "Cliente")
                ->orderBy("id", "desc")
                ->paginate($this->totalPorPagina);
            }else{
                $users = $this->user->selectRaw("users.id, name, cpf, tipo")
                ->whereRaw($where)
                ->orderBy("id", "desc")
                ->paginate($this->totalPorPagina);
            }
        }elseif($info == 'visita'){
            if(!empty($colaborador)){
                $users = $this->user->selectRaw("users.id, name, cpf, tipo")
                ->whereRaw($where)
                ->where("tipo", "!=", "Cliente")
                ->orderBy("id", "desc")
                ->paginate($this->totalPorPagina);
            }else{
                $users = $this->user->selectRaw("users.id, name, cpf, tipo")
                ->whereRaw($where)
                ->where("tipo", "!=", "Cliente")
                ->orderBy("id", "desc")
                ->paginate($this->totalPorPagina);
            }
        }else{
            $users = $this->user->selectRaw("users.id, name, cpf, tipo")
            ->whereRaw($where)
            ->orderBy("id", "desc")
            ->paginate($this->totalPorPagina);
        }
        

        return view('relatorio/filtrar', compact('users', 'info', 'cliente', 'colaborador'));
    }


    public function filtroGeral($info)
    {
        //if($info == 'pedido'){
            session()->forget("formUsuario");
            session()->forget("formDataInicial");
            session()->forget("formDataFinal");
        //}

        if($info == 'colaborador')
        {
            $users = $this->user->selectRaw("users.id, name, cpf, tipo")
                   ->where('tipo', '=', 'vendedor')
                   ->orWhere('tipo', '=', 'administrador')
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);
        }
        elseif($info == 'visita')
        {
            $users = $this->user->selectRaw("users.id, name, cpf, tipo")
                   ->where('tipo', '!=', 'cliente')
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);
        }else{
            $users = $this->user->orderBy("id", "desc")->paginate($this->totalPorPagina);
        }

        return view('relatorio/filtrar', compact('info', 'users'));
    }
    

    public function relatorioGerado(Request $request, $info)
    {
        //==> Global
        $dateForm = $request->all();
        
        $usuario     = isset($dateForm["selecionado"]) ? $dateForm["selecionado"] : session()->get("formUsuario","");
        $dataInicial = isset($dateForm["dateInicial"]) ? $dateForm["dateInicial"] : session()->get("formDataInicial","");
        $dataFinal   = isset($dateForm["dateFinal"])   ? $dateForm["dateFinal"]   : session()->get("formDataFinal","");

        session()->put("formUsuario",$usuario);
        session()->put("formDataInicial",$dataInicial);
        session()->put("formDataFinal",$dataFinal);

        $user = $this->user->find($usuario);

        /*$where = "id > 0";

        if($user != "" && $info == 'cliente'){
            $where .= " and cliente_id = {$user->id} ";
        }
        if($dataInicial != ""){
            $where .= " and date >= '{$dataInicial}' ";
        }
        if($dataFinal != ""){
            $where .= " and date <= '{$dataFinal}' ";
        }

        $pedidos = \DB::table('pedidos')
                ->whereRaw($where)
                ->count();

        $ids = $pedidos = \DB::table('pedidos')
                ->whereRaw($where)
                ->get();*/

        //==> Cliente
        if($user != "" && $info == 'cliente')
        {
            /*session()->forget("formUsuario");
            session()->forget("formDataInicial");
            session()->forget("formDataFinal");*/

            if($dataInicial != "" && $dataFinal != "")
            {
                $pedidos = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->WhereDate('date', '<=', $dataFinal)
                ->count();

                $ids = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->WhereDate('date', '<=', $dataFinal)
                ->get();

            }elseif($dataFinal != ""){
                $pedidos = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '<=', $dataFinal)
                ->count();

                $ids = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '<=', $dataFinal)
                ->get();
            }elseif($dataInicial != ""){
                $pedidos = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->count();
                
                $ids = \DB::table('pedidos')->where('cliente_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->get();
            }else{
                $pedidos = \DB::table('pedidos')->where('cliente_id', '=', $user->id)->count();

                $ids = \DB::table('pedidos')->where('cliente_id', '=', $user->id)->get();
            }

            //==> Global Interno (Cliente)
            $totalDosPedidos = 0;
            foreach ($ids as $key => $valor) 
            {         
                $total = $this->pedido->total($valor->id);
                $totalDosPedidos = $totalDosPedidos + $total;
            }
        }

        //=> Colaborador
        if($user != "" && $info == 'colaborador')
        {
            /*session()->forget("formUsuario");
            session()->forget("formDataInicial");
            session()->forget("formDataFinal")*/

            if($dataInicial != "" && $dataFinal != "")
            {
                $pedidos = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->WhereDate('date', '<=', $dataFinal)
                ->count();

                $ids = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->WhereDate('date', '<=', $dataFinal)
                ->get();
               
            }elseif($dataFinal != ""){
                $pedidos = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
                ->WhereDate('date', '<=', $dataFinal)
                ->count();

                $ids = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
                ->WhereDate('date', '<=', $dataFinal)
                ->get();

            }elseif($dataInicial != ""){
                $pedidos = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->count();
                
                $ids = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)
                ->WhereDate('date', '>=', $dataInicial)
                ->get();

            }else{
                $pedidos = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)->count();

                $ids = \DB::table('pedidos')->where('colaborador_id', '=', $user->id)->get();
            }

            //==> Global Interno (Colaborador)
            $totalDosPedidos = 0;
            $totalBase = 0;
            foreach ($ids as $key => $valor) 
            {         
                $total = $this->pedido->total($valor->id);
                $totalDosPedidos = $totalDosPedidos + $total;

                $totalB = $this->pedido->totalBase($valor->id);
                $totalBase = $totalBase + $totalB;
            }
        }
        
        //Pedido
        if($info == 'pedido')
        {
            if($user != "" && $dataInicial != "" && $dataFinal != "")
            {
                if($user->tipo == 'Cliente'){
                    $ped = $this->pedido->selectRaw("pedidos.id, date, c.name as cliente, v.name as colaborador")
                   ->join("users as c","c.id","=","cliente_id") 
                   ->join("users as v","v.id","=","colaborador_id") 
                   ->where('cliente_id', '=', $user->id)
                   ->WhereDate('date', '>=', $dataInicial)
                   ->WhereDate('date', '<=', $dataFinal)
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);
                }else{
                    $ped = $this->pedido->selectRaw("pedidos.id, date, c.name as cliente, v.name as colaborador")
                   ->join("users as c","c.id","=","cliente_id") 
                   ->join("users as v","v.id","=","colaborador_id") 
                   ->where('colaborador_id', '=', $user->id)
                   ->WhereDate('date', '>=', $dataInicial)
                   ->WhereDate('date', '<=', $dataFinal)
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);
                }
                
               
            }elseif($user != "" && $dataFinal != ""){
                if($user->tipo == 'Cliente'){
                    $ped = $this->pedido->selectRaw("pedidos.id, date, c.name as cliente, v.name as colaborador")
                       ->join("users as c","c.id","=","cliente_id") 
                       ->join("users as v","v.id","=","colaborador_id") 
                       ->where('cliente_id', '=', $user->id)
                       ->WhereDate('date', '<=', $dataFinal)
                       ->orderBy("id", "desc")
                       ->paginate($this->totalPorPagina);
                }else{
                    $ped = $this->pedido->selectRaw("pedidos.id, date, c.name as cliente, v.name as colaborador")
                       ->join("users as c","c.id","=","cliente_id") 
                       ->join("users as v","v.id","=","colaborador_id") 
                       ->where('colaborador_id', '=', $user->id)
                       ->WhereDate('date', '<=', $dataFinal)
                       ->orderBy("id", "desc")
                       ->paginate($this->totalPorPagina);
                }

            }elseif($user != "" && $dataInicial != ""){
                if($user->tipo == 'Cliente'){
                    $ped = $this->pedido->selectRaw("pedidos.id, date, c.name as cliente, v.name as colaborador")
                       ->join("users as c","c.id","=","cliente_id") 
                       ->join("users as v","v.id","=","colaborador_id") 
                       ->where('cliente_id', '=', $user->id)
                       ->WhereDate('date', '>=', $dataInicial)
                       ->orderBy("id", "desc")
                       ->paginate($this->totalPorPagina);
                }else{
                    $ped = $this->pedido->selectRaw("pedidos.id, date, c.name as cliente, v.name as colaborador")
                       ->join("users as c","c.id","=","cliente_id") 
                       ->join("users as v","v.id","=","colaborador_id") 
                       ->where('colaborador_id', '=', $user->id)
                       ->WhereDate('date', '>=', $dataInicial)
                       ->orderBy("id", "desc")
                       ->paginate($this->totalPorPagina);
                }

            }else{
                if($user != ""){
                    if($user->tipo == 'Cliente'){
                        $ped = $this->pedido->selectRaw("pedidos.id, date, c.name as cliente, v.name as colaborador")
                           ->join("users as c","c.id","=","cliente_id") 
                           ->join("users as v","v.id","=","colaborador_id") 
                           ->where('cliente_id', '=', $user->id)
                           ->orderBy("id", "desc")
                           ->paginate($this->totalPorPagina);
                    }else{
                        $ped = $this->pedido->selectRaw("pedidos.id, date, c.name as cliente, v.name as colaborador")
                           ->join("users as c","c.id","=","cliente_id") 
                           ->join("users as v","v.id","=","colaborador_id") 
                           ->where('colaborador_id', '=', $user->id)
                           ->orderBy("id", "desc")
                           ->paginate($this->totalPorPagina);
                    }
                } 
            }
        if(isset($ped)){
            foreach ($ped as $key => $valor){         
                $total[$key] = $this->pedido->total($valor->id);
            }    
        }
        
        }

        //Visita
        if($user != "" && $info == 'visita')
        {
            if($dataInicial != "" && $dataFinal != "")
            {
                $visitas = $this->visita->selectRaw("visitas.id, date, colaborador_id, cliente_id, c.name as cliente, cid.name as cidade")
                    ->join("users as c","c.id","=","cliente_id")
                    ->join("cidades as cid","cid.id","=","c.cidade_id")
                    ->where('colaborador_id', '=', $user->id)
                    ->WhereDate('date', '>=', $dataInicial)
                    ->WhereDate('date', '<=', $dataFinal)
                    ->orderBy("date", "desc")
                    ->paginate($this->totalPorPagina);
               
            }elseif($dataFinal != ""){
                $visitas = $this->visita->selectRaw("visitas.id, date, colaborador_id, cliente_id, c.name as cliente, cid.name as cidade")
                    ->join("users as c","c.id","=","cliente_id")
                    ->join("cidades as cid","cid.id","=","c.cidade_id")
                    ->where('colaborador_id', '=', $user->id)
                    ->WhereDate('date', '<=', $dataFinal)
                    ->orderBy("date", "desc")
                    ->paginate($this->totalPorPagina);

            }elseif($dataInicial != ""){
                $visitas = $this->visita->selectRaw("visitas.id, date, colaborador_id, cliente_id, c.name as cliente, cid.name as cidade")
                    ->join("users as c","c.id","=","cliente_id")
                    ->join("cidades as cid","cid.id","=","c.cidade_id")
                    ->where('colaborador_id', '=', $user->id)
                    ->WhereDate('date', '>=', $dataInicial)
                    ->orderBy("date", "desc")
                    ->paginate($this->totalPorPagina);

            }else{
                $visitas = $this->visita->selectRaw("visitas.id, date, colaborador_id, cliente_id, c.name as cliente, cid.name as cidade")
                    ->join("users as c","c.id","=","cliente_id")
                    ->join("cidades as cid","cid.id","=","c.cidade_id")
                    ->where('colaborador_id', '=', $user->id)
                    ->orderBy("date", "desc")
                    ->paginate($this->totalPorPagina);
            }

        }
        
        if($info == 'cliente'){
            return view('relatorio/geradoCliente', compact('user', 'dataInicial', 'dataFinal', 'pedidos', 'info', 'totalDosPedidos'));
        }
        if($info == 'colaborador'){
            return view('relatorio/geradoColaborador', compact('user', 'dataInicial', 'dataFinal', 'pedidos', 'info', 'totalDosPedidos', 'totalBase'));
        }
        if($info == 'pedido'){
            return view('relatorio/geradoPedido', compact('user', 'dataInicial', 'dataFinal', 'pedidos', 'info', 'ped', 'total'));
        } 
        if($info == 'visita'){
            return view('relatorio/geradoVisita', compact('user', 'visitas', 'dataInicial', 'dataFinal'));
        }
    }

}
