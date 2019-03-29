<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Cidade;
use App\Http\Controllers\Controller;
use App\Http\Requests\CidadeFormRequest;

class CidadeController extends Controller
{
    private $cidade;
    private $totalPorPagina = 6;

    public function __construct(Cidade $cidade)
    {
        $this->cidade = $cidade;
    }
    
/** 
* [ DE ONDE VEM? (do menu)] 
* [ OQ RECEBE? (nada)]
* [ PRA ONDE VAI? (para view indexCidade de cidade)]
* [ OQ FAZ? (1-> recupera os cadastros de cidades na ordem decrecente conforme seu id e com a quantidade de paginas/
* 2-> envia/) ]
*/
    public function index()
    {
        $title = 'Lista de Cidades';

        $cidades = $this->cidade->orderBy("id", "desc")->paginate($this->totalPorPagina);

        return view('cidade.indexCidade', compact('title', 'cidades'));
    }

/** 
* [ DE ONDE VEM? (da view indexCidade de cidade - botão cadastrar)] 
* [ OQ RECEBE? (nada)]
* [ PRA ONDE VAI? (para view criarCidade de cidade)]
* [ OQ FAZ? (1-> define o titulo/ 2-> envia) ]
*/
    public function create()
    {
        $title = 'Cadastro de Cidade';
        
        return view('cidade/criarCidade', compact('title'));
    }
    
/** 
* [ DE ONDE VEM? (da view criarCidade de cidade)] 
* [ OQ RECEBE? (dados do formolario)]
* [ PRA ONDE VAI? (metodo index ou store da CidadeController)]
* [ OQ FAZ? (1-> recupera os dados do formulario/ 2-> cadastra) ]
*/
    public function store(CidadeFormRequest $request)
    {
        $dataForm = $request->all();

        $insert = $this->cidade->create($dataForm);

        if($insert){
            return redirect()->route('cidades.index', ['success' => 'ok'])->with(['success' => 'ok']);
        }else{
            return redirect()->route('cidades.store');
        }
    }

/** 
* [ DE ONDE VEM? ()] 
* [ OQ RECEBE? ()]
* [ PRA ONDE VAI? ()]
* [ OQ FAZ? (1->/) ]
*/
    public function show($id)
    {
        //
    }

/** 
* [ DE ONDE VEM? (da view indexCidade de cidade)] 
* [ OQ RECEBE? (o id da cidade)]
* [ PRA ONDE VAI? (para a view criarCidade em cidade)]
* [ OQ FAZ? (1-> recupera os dados pelo id/ 2-> define o titulo/ 3-> envia) ]
*/
    public function edit($id)
    {
        $cidade = $this->cidade->find($id);

        $title = 'Editar Cidade';

        return view ('cidade/criarCidade', compact('cidade', 'title'));
    }

/** 
* [ DE ONDE VEM? (da view criarCidade de cidade)] 
* [ OQ RECEBE? (dados do formulario e o id da cidade)]
* [ PRA ONDE VAI? (para o metodo index ou edit do CidadeController)]
* [ OQ FAZ? (1-> recupera os dados do formulario/ 2-> recupera a cidade pelo id/ 3-> atualiza) ]
*/
    public function update(CidadeFormRequest $request, $id)
    {
        $dataForm = $request->all();

        $cidade = $this->cidade->find($id);

        $update = $cidade->update($dataForm);

        if($update){
            return redirect()->route('cidades.index', ['success' => 'ok'])->with(['success' => 'ok']);
        }else{
            return redirect()->route('cidades.edit', $id)->with(['errors' => 'Falha ao editar']);
        }
    }

/** 
* [ DE ONDE VEM? (da view indexCidade de cidade - botão deletar(lixeira))] 
* [ OQ RECEBE? (metodo acionado pelo botão conforme o id atual)]
* [ PRA ONDE VAI? (para o metodo index)]
* [ OQ FAZ? (1-> recupera a cidade pelo id/ 2-> deleta) ]
*/
    public function destroy($id)
    {
        $cidade = $this->cidade->find($id);

        $delete = $cidade->delete();

        if($delete){
            return redirect()->route('cidades.index');
        }
    }

/** 
* [ DE ONDE VEM? ()] 
* [ OQ RECEBE? ()]
* [ PRA ONDE VAI? ()]
* [ OQ FAZ? (1->/) ]
*/
    public function combo()
    {
        try{
            $response = array("data" => $this->cidade->combo(),"status" => 1);

        }catch(\Exception $e){
            $response = array("data" => $e->getMessage(),"status" => 500);
        } 
        return response()->json($response);   
    }

/** 
* [ DE ONDE VEM? (da view indexCidade de cidade)] 
* [ OQ RECEBE? (os dados do form e o nome da cidade ou parte do nome)]
* [ PRA ONDE VAI? (para propria view indexCidade em cidade)]
* [ OQ FAZ? (1-> recupera os dados do form exceto o token/ 2->compara o nome da cidade ou parte dele com as 
* cadastradas) ]
*/
    public function filtro(Request $request, Cidade $cidade)
    {
        $dataForm = $request->except('_token');

        $cidade = isset($dataForm["name"]) ? $dataForm["name"] : "";
        $where   = "cidades.id > 0";

        if(!empty($cidade)){
            $where = "name LIKE '%{$cidade}%'";
        }

        $cidades = $this->cidade->selectRaw("cidades.id, name")
                   ->whereRaw($where)
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);

        return view ('cidade/indexCidade', compact('cidades', 'dataForm', 'cidade'));
    }

}
