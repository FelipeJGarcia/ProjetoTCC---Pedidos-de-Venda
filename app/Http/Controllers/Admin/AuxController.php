<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PedidoItem;
use App\Models\Pedido;
use App\Models\Cidade;
use App\Models\User;
use App\Models\Visita;
use App\Http\Requests\CidadeFormRequest;
use App\Http\Requests\VisitaFormRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input as Input;

class AuxController extends Controller
{
    private $cidade;
    private $user;
    private $pedido;
    private $visita;
    private $totalPorPagina = 6;

    // cria o objeto de product e joga na variavel product
    public function __construct(Cidade $cidade, PedidoItem $pedidoItem, Pedido $pedido, User $user, Visita $visita) { 
        $this->cidade = $cidade;
        $this->pedidoItem = $pedidoItem;
        $this->pedido = $pedido;
        $this->user = $user;
        $this->visita = $visita;
    }
    
/** 
* [ DE ONDE VEM? ()] 
* [ OQ RECEBE? ()]
* [ PRA ONDE VAI? ()]
* [ OQ FAZ? (1->/) ]
*/
    public function index()
    {
        $pedidos = $this->pedido->selectRaw("pedidos.id, status, date, c.name as cliente, v.name as colaborador")
                   ->join("users as c","c.id","=","cliente_id") 
                   ->join("users as v","v.id","=","colaborador_id") 
                   ->where("status", "=", "3")
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina); 
        if(auth()->user()->tipo == "Administrador"){
            return view('admin/home/index', compact('pedidos'));
        }else{
            return redirect()->route('home.vendedor');
        }  
    }


    public function deleteItemPedido($id)
    {
        $item = $this->pedidoItem->find($id);

        $delete = $item->delete();       

        if ($delete){
            return Redirect::back();
        }
    }

    //função consultando
    public function conferiPedido($id)
    {
        $pedido = $this->pedido->find($id);

        $title = "Pedido: ";

        $pedido->status = 4;
        $pedido->update();

        return redirect()->route('pedidos.show', $pedido);
    }

    //validar pedido
    public function validarPedido($id)
    {
        $pedido = $this->pedido->find($id);

        $pedido->status = 5;
        $pedido->update();

        return redirect()->route("aux.index");
    }


/** 
* [ DE ONDE VEM? (botão cadastrar da view index do user) ] 
* [ OQ RECEBE? (nada)]
* [ PRA ONDE VAI? (view escolha do user) ]
* [ OQ FAZ? (1-> estabelece o titulo e os tipos/ 2-> envia) ]
*/
    public function cadUserEscolha()
    {
        $title = 'Escolha o Tipo de Cadastro';
        
        $tipo = ['Cliente', 'Colaborador'];
        
        return view('user/escolha', compact('title', 'tipo'));
    }

/** 
* [ DE ONDE VEM? (da view escolha do user)] 
* [ OQ RECEBE? (dados de formulario - tipo)]
* [ PRA ONDE VAI? (se colaborador para view criar do user, se cliente para view criarCliente do user)]
* [ OQ FAZ? (1-> estabelece os tipo de colaborador/ 2-> recupera o tipo do cadastro/ 
* 3-> recebe as cidades pelo metodo combo/ 4-> envia) ]
*/
    public function formView(Request $request)
    {    
        $tipo = ['Administrador', 'Vendedor']; 
        
        $pessoa = $request->input('tipo') ? $request->input('tipo') : 'Colaborador';
        
        $cidades = Cidade::combo();
        
        if($pessoa == 'Colaborador'){
            return view('user/criar', compact('tipo', 'cidades'));
        }else{
            return view('user/criarCliente',compact('cidades'));
        } 
    }


    public function homeVendedor()
    {
        $users = $this->user->selectRaw("users.id, users.name, rua, numero, c.name as cidade")
                   ->join("cidades as c","c.id","=","cidade_id") 
                   ->where("visita_id", "=", auth()->user()->id)
                   ->orderBy("c.id", "desc")
                   ->paginate($this->totalPorPagina);

        return view('admin/home/indexVisita', compact('users'));
    }


    public function confVisita()
    {   

        $select  = "users.id, users.name, cpf, c.name as cid,";
        $select .= "(SELECT DATE_FORMAT(MAX(date),'%d-%m-%Y') as date FROM visitas WHERE cliente_id = users.id) as ultima_visita";

        $users = $this->user->selectRaw($select)
                   ->join("cidades as c","c.id","=","cidade_id") 
                   ->where("visita_id", "=", "0")
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);

        $cidades = Cidade::combo();

        return view('admin/home/confVisitas', compact('users', 'cidades'));
    }


    public function filtroCidadeVisitas(Request $request)
    {
        $dataForm = $request->except('_token');
        //--------------------------------------------------------------------
        $cidade = isset($dataForm["cidade_id"]) ? $dataForm["cidade_id"] : "";
        if($cidade != ""){
            session()->put("formCidade", $cidade);
        }
        
        if($cidade == ""){
            $cidade = session()->get("formCidade");
        }
        //esta sessão não esta sendo fechada
        //session()->forget("formCidade");
        //--------------------------------------------------------------------
        $where = "c.id > 0"; 

        if(!empty($cidade)){
            $where = "c.id = {$cidade}";
            if($cidade == 4){
                return redirect()->route("conf.visita"); 
            }
        }

        $select  = "users.id, users.name, cpf, c.name as cid,";
        $select .= "(SELECT DATE_FORMAT(MAX(date),'%d-%m-%Y') as date FROM visitas WHERE cliente_id = users.id) as ultima_visita";

        $users = $this->user->selectRaw($select)
                   ->join("cidades as c","c.id","=","cidade_id") 
                   ->where("visita_id", "=", "0")
                   ->whereRaw($where)
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);

        $cidades = Cidade::combo();
        
        return view('admin/home/confVisitas', compact('users', 'cidades', 'cidade'));
    }



    public function addListaVisita(Request $request)
    {
        $dateForm = $request->all();
        
        $usuario = isset($dateForm["selecionado"]) ? $dateForm["selecionado"] : "";

        $user = $this->user->find($usuario);

        if($user != ""){
            $user->visita_id = auth()->user()->id;
            $user->update();
        }
        
        return redirect()->route("conf.visita");
    }


    public function removeListaVisita($id)
    {
        $user = $this->user->find($id);

        $user->visita_id = 0;
        $user->update();
        
        return redirect()->route("home.vendedor");
    }

    public function registraVisita(VisitaFormRequest $request)
    {
        $dateForm = $request->all();
        
        $usuario = isset($dateForm["selecionado"]) ? $dateForm["selecionado"] : "";

        $user = $this->user->find($usuario);

        $visita = new Visita;

        if($user != ""){
            $user->visita_id = 0;
            $user->update();

            $visita->cliente_id = $user->id;
            $visita->colaborador_id = auth()->user()->id;
            $visita->date = date('Ymd');
            $visita->save();
        }
        
        return redirect()->route("home.vendedor");
    }


    public function emManutencao(){

        echo "EM MANUTENÇÃO !!";

    }
    
}