<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Product;
use App\Models\User;
use App\Http\Requests\ItemPedidoFormRequest;
use App\Http\Requests\UserFormRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\PedidoFormRequest;

class PedidoController extends Controller
{
    private $product;
    private $pedido;
    private $user;
    private $totalPorPagina = 6;   // cria uma variavel q armazena o valor de quantidade de itens a ser mostrado
 
    public function __construct(Pedido $pedido, User $user, Product $product, PedidoItem $pedidoItem) { 
        $this->product = $product;
        $this->pedido = $pedido;
        $this->user = $user;
        $this->pedidoItem = $pedidoItem;
    }
    
    public function index()
    {
        $users = $this->user->orderBy("id", "desc")->paginate($this->totalPorPagina);

        return view ('pedido/index', compact('users'));
    }


    public function getClienteId(Request $request, $id)
    {
        $users = $this->user->find($id);

        $vendedores = $this->user->select("id","name")->where("tipo","Vendedor")->orWhere("tipo","Administrador")->get();

        return view('pedido/escolhaVendedor', compact('vendedores', 'users'));
    }
    

    public function create(PedidoFormRequest $request)
    {   
        $cliente   = "";
        $vendedor  = "";

        $products = $this->product->orderBy("id","desc")->paginate($this->totalPorPagina);

        $dataForm = $request->all();

        $pedido = $this->pedido->create($dataForm);

        $pedido->date = date('Ymd');
        $pedido->update();

        //inicio a sessão
        session()->put("pedido_id", $pedido->id);

        $cliente  = $this->user->getNameById($pedido->cliente_id); 
        $vendedor = $this->user->getNameById($pedido->colaborador_id);

        return redirect()->route("pedido.produtosListaAdd");        
    }

    
    public function store(PedidoFormRequest $request)
    {
        // o Pedido já esta sendo criado na função create
    }

    
    public function show($id)
    {
        $title = 'Pedido:';

        $pedido = $this->pedido->selectRaw("pedidos.id, status, date, observacao, c.name as cliente, v.name as colaborador")
                   ->join("users as c","c.id","=","cliente_id") 
                   ->join("users as v","v.id","=","colaborador_id")
                   ->where("pedidos.id", "=", $id)->get()[0];

        $itens = $this->pedido->itensPedido($id);

        $total = $this->pedido->total($id);
        /*echo "<pre>";
        var_dump($itens); 
        echo "</pre>";*/
      
        return view('pedido/gerenciar/mostrar', compact('title', 'pedido', 'itens', 'total'));
    }

    
    public function edit($id)
    {
        $title = 'Editar Pedido';

        $pedido = $this->pedido->selectRaw("pedidos.id, observacao, status, c.name as cliente, v.name as colaborador")
                   ->join("users as c","c.id","=","cliente_id") 
                   ->join("users as v","v.id","=","colaborador_id")
                   ->where("pedidos.id", "=", $id)->get()[0];

        $itens = $this->pedido->itensPedido($id);

        if($pedido->status == 2)
        {
            $pedido->status = 2;
            $pedido->update();
        }

        $total = $this->pedido->total($id);
        
        return view('pedido/gerenciar/editar', compact('title', 'pedido', 'itens', 'total'));
    }


    public function encerrarPedido($id)
    {
        $pedido = $this->pedido->find($id);

        $pedido->status = 3;
        $pedido->update();

        return redirect()->route("aux.index");

    }


    public function update(Request $request, $id)
    {
        try{

            \DB::beginTransaction();

            $dataForm = $request->all();

            if(isset($dataForm["itens"])){
                foreach ($dataForm["itens"] as $key => $value) {
                    PedidoItem::find($value["id"])->update($value);
                }
            }

            $pedido = $this->pedido->find($id);
            if($pedido->status == 2)
            {
                $pedido->status = 3;
            }
            
            $pedido->update($dataForm);
            \DB::commit();

            if($pedido->status == 3 or $pedido->status == 5)
            {
                return redirect()->route('pedido.mostrar', ['success' => 'ok'])->with(['success' => 'ok']);
            }elseif ($pedido->status == 4) {
                return redirect()->route('pedidos.show', $pedido->id);
                $pedido->status = 3;
                $pedido->update();
            }else{
                return redirect()->route('pedido.produtosListaAdd');
            }
            
        
        }catch(\Exception $e){

            //throw new \Exception($e->getMessage(),500);
            
            \DB::rollBack();
        }    

    }

    //Função para cancelar o pedido
    public function cancelarPedido($id)
    {
        $pedido = $this->pedido->find($id);
        $pedido->delete();

        return redirect()->route("aux.index");
    }

    
    public function destroy($id)
    {
        $pedido = $this->pedido->find($id);
           
        $pedido->delete();

        if($pedido->status == 4 or $pedido->status == 1){
            return redirect() ->route ('aux.index');
        }else{
            return redirect() ->route ('pedido.mostrar');
        }
        
    }

    public function voltarHome($id)
    {
        $pedido = $this->pedido->find($id);

        if($pedido->status == 4)
        {
            $pedido->status = 3;
            $pedido->update();
            return redirect() ->route ('aux.index');
        }else{
            return redirect() ->route ('pedido.mostrar');
        }
    }

    //função para voltar da edição ao index setando o status do pedido como 3 (validar)
    public function voltarDePedidos($id)
    {
        $pedido = $this->pedido->find($id);

        if($pedido->status == 2)
        {
            $pedido->status = 3;
            $pedido->update();

            return redirect() ->route ('pedido.mostrar');
        }elseif ($pedido->status == 4 or $pedido->status == 3 or $pedido->status == 5)
        {
            return redirect()->route('pedidos.show', $pedido->id);
            if($pedido->status == 4){
                $pedido->status = 3;
            }
            $pedido->update();
        }else{
            return redirect() ->route ('pedido.produtosListaAdd');
        }
        
    }

    //Index do pedido no gerenciar
    public function mostrarPedidos()
    {          
        $pedidos = $this->pedido->selectRaw("pedidos.id, status, date, c.name as cliente, v.name as colaborador")
                   ->join("users as c","c.id","=","cliente_id") 
                   ->join("users as v","v.id","=","colaborador_id") 
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina); 
        
        return view('pedido/gerenciar/mostrarPedido', compact('pedidos'));
    }

    public function filtros(Request $request, User $user)
    {
        $dataForm = $request->except('_token');
        $id      = isset($dataForm["id"]) ? $dataForm["id"] : "";
        $cliente = isset($dataForm["cliente"]) ? $dataForm["cliente"] : "";
        $colaborador = isset($dataForm["colaborador"]) ? $dataForm["colaborador"] : "";
        $data = isset($dataForm["date"]) ? $dataForm["date"] : "";
        $where   = "pedidos.id > 0"; 

        if(!empty($id)){
            $where = "pedidos.id = {$id}";
        }elseif(!empty($cliente)){
            $where = "c.name LIKE '%{$cliente}%'";
        }elseif(!empty($data)){
            $where = "date LIKE '%{$data}%'";
        }else{
            $where = "v.name LIKE '%{$colaborador}%'"; 
        }

        $pedidos = $this->pedido->selectRaw("pedidos.id, status, date, c.name as cliente, v.name as colaborador")
                   ->join("users as c","c.id","=","cliente_id") 
                   ->join("users as v","v.id","=","colaborador_id")
                   ->whereRaw($where)
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);
        
        return view('pedido/gerenciar/mostrarPedido', compact('pedidos', 'id', 'cliente', 'colaborador', 'data'));
    }

    
    public function filtroPedidoProduto(Request $request, Product $product)   
    {
        $dataForm = $request->except('_token');
        $produto = isset($dataForm["name"]) ? $dataForm["name"] : "";
        $where   = "products.id > 0";

        $pedido_id = session()->get("pedido_id",null);
        $pedido   = $this->pedido->find($pedido_id);
        $cliente  = $this->user->getNameById($pedido->cliente_id); 
        $vendedor = $this->user->getNameById($pedido->colaborador_id);
        
        if(!empty($produto)){
            $where = "name LIKE '%{$produto}%'";
        }

        $products = $this->product->selectRaw("products.id, name, valor")
                   ->whereRaw($where)
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);

        return view('pedido/criar', compact('products', 'dataForm', 'pedido', 'cliente', 'vendedor', 'produto'));
    }
    

    public function pedidoListaItens(){

        $pedido_id = session()->get("pedido_id",null);
        $cliente   = "";
        $vendedor  = "";
        $products = $this->product->orderBy("id","desc")->paginate($this->totalPorPagina);

        if(!is_null($pedido_id)){

            $pedido   = $this->pedido->find($pedido_id);
            $cliente  = $this->user->getNameById($pedido->cliente_id); 
            $vendedor = $this->user->getNameById($pedido->colaborador_id); 

        }

        return view('pedido/criar', compact('products', 'cliente', 'vendedor', 'pedido'));
    }


    public function addItemPedido($id)
    {
        //fecha a sessão
        session()->forget("pedido_id");

        //abra a sessão
        $pedido = $this->pedido->find($id);
        session()->put("pedido_id", $pedido->id);

        $cliente  = $this->user->getNameById($pedido->cliente_id); 
        $vendedor = $this->user->getNameById($pedido->colaborador_id);

        return redirect()->route("pedido.produtosListaAdd");
    }


    /* session()->forget("pedido_id"), para excluir a sessão
    * 
    */
    public function createItemPedido(ItemPedidoFormRequest $request){

        try{
            $params              = $request->all();
            $pedido_id           = session()->get("pedido_id");

            $params["pedido_id"] = $pedido_id;
            $validator           = \Validator::make($params,$this->pedido->rulesItem(),$this->pedido->messagesItem());
           
            if($validator->passes()){
                
                $item = PedidoItem::create(array(
                    "pedido_id"  => $pedido_id,
                    "quantidade" => $params["quantidade"],
                    "valor"      => $params["valor"],
                    "produto_id" => $params["produto_id"]                
                ));

                return redirect()->route("pedido.produtosListaAdd");

            }else{
                return redirect()->back()->withErrors($validator)->withInput();
            }   
        }catch(\Exception $e){
            var_dump($e->getMessage());
        }
    }


    public function addProduto($produto_id, $pedido_id)
    {
        $product = $this->product->find($produto_id);
        $title = 'Adicionar Produto';

        return view('pedido/add', compact('title','product','produto_id','pedido_id'));
    }

}