<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;                      // classe da model
use App\Http\Requests\UserFormRequest;    // usado no store
use App\Http\Requests\CidadeFormRequest;
use App\Models\Cidade;
use Illuminate\Support\Facades\Input as Input;

class UserController extends Controller
{
    private $user;
    //cria uma variavel q armazena o valor de quantidade de itens a ser mostrado por paginação
    private $totalPorPagina = 6;   
 
    public function __construct(User $user) { 
        $this->user = $user;
    }
    
/** 
* [ DE ONDE VEM? (do menu) ] 
* [ OQ RECEBE? (nada)]
* [ PRA ONDE VAI? (view index do user) ]
* [ OQ FAZ? (1-> recupera os cadastros de pessoas na ordem decrecente conforme seu id e com a quantidade de paginas/
* 2-> envia) ]
*/
    public function index()
    {   
        $users = $this->user->orderBy("id", "desc")->paginate($this->totalPorPagina);

        return view('user/index', compact('users'));  
    }
    
/** 
* [ DE ONDE VEM? ()] 
* [ OQ RECEBE? ()]
* [ PRA ONDE VAI? ()]
* [ OQ FAZ? (1->/) ]
*/
    public function create()
    {
        // Estou utilizando uma outra tela de seleção ou cliente ou colaborador em AuxController
    }
   
/** 
* [ DE ONDE VEM? (do form criar ou criarCliente do user) ] 
* [ OQ RECEBE? (dados de formulario)]
* [ PRA ONDE VAI? (metodo index ou store do user) ]
* [ OQ FAZ? (1->recupera os dados do form/ 2-> cadastra) ]
*/
    public function store(UserFormRequest $request)
    {
        $dataForm = $request->all();

        if(isset($dataForm["password"])){
            $dataForm["password"] = bcrypt($dataForm["password"]);
        }
        // recupera o token do form
        //$request->input('_token');

        $insert = $this->user->create($dataForm);
        
        if($insert){
            return redirect()->route('user.index', ['success' => 'ok'])->with(['success' => 'ok']);
        } else{
            return redirect()->route('user.store');
        }
    }
    
/** 
* [ DE ONDE VEM? (da view index do user - botão consultar (lupa))] 
* [ OQ RECEBE? (o id do usuário)]
* [ PRA ONDE VAI? (para view mostrar do user)]
* [ OQ FAZ? (1-> recupera dados da pessoa pelo id/ 2-> recebe as cidades pelo metodo combo/ 3-> envia) ]
*/
    public function show($id)    
    {
        $user = $this->user->find($id);
        
        if($user->tipo != 'Cliente'){
            $title = 'Colaborador:';
        }else{
            $title = 'Cliente:';
        }

        $cidades = Cidade::combo();
        
        return view('user/mostrar', compact('user', 'title', 'cidades'));
    }

/** 
* [ DE ONDE VEM? (da view index do user - botão editar (lapis))] 
* [ OQ RECEBE? (o id do usuário)]
* [ PRA ONDE VAI? (se for colaborador para view criar do user, se for cliente para view criarCliente do user)]
* [ OQ FAZ? (1-> recupera dados da pessoa pelo id/ 2-> recebe as cidades pelo metodo combo/ 3-> envia) ]
*/
    public function edit($id)   
    {     
        $user = $this->user->find($id);

        $cidades = Cidade::combo();
        
        if($user->tipo != 'Cliente'){
            $title = 'Editar Colaborador';
            $tipo = ['Administrador', 'Vendedor'];
            return view('user/criar', compact('title', 'user', 'tipo', 'cidades'));
        }else{
            $title = 'Editar Cliente';
            return view('user/criarCliente', compact('title', 'user', 'cidades'));
        }
    }
    
/** 
* [ DE ONDE VEM? (do form de edição, se dor cliente da view criarCliente do user, se for colaborador da view criar do user)] 
* [ OQ RECEBE? (o id do usuário e recupera dos dados do form)]
* [ PRA ONDE VAI? (para view index do user ou edit do user)]
* [ OQ FAZ? (1-> recupera os dados do form/ 2-> recupera a pessoa pelo id/ 3 - atualiza) ]
*/
    public function update(UserFormRequest $request, $id)
    {
        $dataForm = $request->all();
        
        $user = $this->user->find($id);

        if($user->tipo != 'Cliente'){
            if($dataForm["password"] == ""){  // se não editou senha
                $dataForm["password"] = $user->password; // recebe a mesma senha antiga
            }else{ // se editou
                $dataForm["password"] = bcrypt($dataForm["password"]); //recebe a nova criptografada
            }
        }
        
        $update = $user->update($dataForm);
        
        if($update){
            return redirect()->route('user.index', ['success' => 'ok'])->with(['success' => 'ok']);
        }else{
            return redirect()->route('user.edit', $id)->with(['errors' => 'Falha ao editar']);
        }
    }

/** 
* [ DE ONDE VEM? (da view mostrar do user - botão deletar)] 
* [ OQ RECEBE? (metodo acionado pelo botão conforme o id atual da view mostrar)]
* [ PRA ONDE VAI? (para o metodo index ou metodo show)]
* [ OQ FAZ? (1-> recupera a pessoa pelo id/ 2-> deleta) ]
*/
    public function destroy($id)
    {
        $user = $this->user->find($id);

        $delete = $user->delete();
        
        if ($delete){
            return redirect() ->route ('user.index');
        }
        else{
            return redirect() ->route ('user.show', $id) -> with (['errors' => 'Falha ao Deletar' ]);
        }
    }

/** 
* [ DE ONDE VEM? (da view index do user)] 
* [ OQ RECEBE? (o nome ou parte do nome para busca)]
* [ PRA ONDE VAI? (para propria view index do user)]
* [ OQ FAZ? (1-> recupera os dados do form, exceto o token/ 2-> compara o nome ou parte do nome recebedo com
* os nomes das pessoas cadastradas pelo metodo search da model User) ]
*/
    public function filtro(Request $request, User $user)
    {
        $dataForm = $request->except('_token');

        //$users = $user->search($dataForm, $this->totalPorPagina);
        $usuario = isset($dataForm["name"]) ? $dataForm["name"] : "";
        $where   = "users.id > 0";

        if(!empty($usuario)){
            $where = "name LIKE '%{$usuario}%'";
        }

        $users = $this->user->selectRaw("users.id, name, cpf, tipo")
                   ->whereRaw($where)
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);

        return view('user/index', compact('users', 'dataForm', 'usuario'));
    }

/** 
* [ DE ONDE VEM? (da view index do pedido)] 
* [ OQ RECEBE? (o nome ou parte do nome para busca)]
* [ PRA ONDE VAI? (para propria view index do pedido)]
* [ OQ FAZ? (1-> !! PROBLEMAS NESTE FILTRO !!) ]
*/
    public function buscarClientePedido(Request $request, User $user)   //Product vem da model, função para buscar
    {
        $dataForm = $request->except('_token');

        $users = $user->search($dataForm, $this->totalPorPagina);

        return view('pedido/index', compact('users', 'dataForm', 'colaboradores','vendedores'));
    }

/** 
* [ DE ONDE VEM? (da view index do pedido - botão consulta (lupa))] 
* [ OQ RECEBE? (o id do usuário)]
* [ PRA ONDE VAI? (para view mostrarNoPedido do user)]
* [ OQ FAZ? (1-> recupera dados da pessoa pelo id/ 2-> recebe as cidades pelo metodo combo/ 3-> envia) ]
*/
    public function pedidoMostraCliente($id)
    {
        $user = $this->user->find($id);
        
        $title = 'Cliente:';

        $cidades = Cidade::combo();
        
        return view('user/mostrarNoPedido', compact('user', 'title', 'cidades'));
    }
}
