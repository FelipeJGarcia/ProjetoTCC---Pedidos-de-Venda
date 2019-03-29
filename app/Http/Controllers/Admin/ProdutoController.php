<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;    // classe da model
use App\Models\Pedido;
use App\Models\ProductPhoto; 
use App\Http\Requests\ProductFormRequest;    // usado no store
use Session;
use Redirect;

class ProdutoController extends Controller
{
    private $product;
    private $pedido;
    private $productPhoto;
    private $totalPorPagina = 6;   // cria uma variavel q armazena o valor de quantidade de itens a ser mostrado


    public function __construct(Product $product, ProductPhoto $productPhoto, Pedido $pedido) { // cria o objeto de product e joga na variavel product
        $this->product = $product;
        $this->pedido = $pedido;
        $this->productPhoto = $productPhoto;
    }
    
    public function index(){
        
        $title = 'Lista de Produtos';  // criando a variavel titulo q sera passada para a view
        
        $products = $this->product->orderBy("id","desc")->paginate($this->totalPorPagina);  // pegando os dados pela model
        
        return view('produto/index', compact('products', 'title'));  //passando para a view oq acabou de ser recuperado do banco de dados
    }                                                          // e também a variavel titulo (title)

    
    public function create(){  
        
        $title = 'Cadastro de Produto';
        
        return view('produto/criar', compact('title'));
    }

    
    public function store(ProductFormRequest $request){   // realizar o cadastro. (Injetado a dependencia do request)
        
        //Pega todos dados do formulário e salva na variavel dataForm
        $dataForm = $request->all();
    
        $produto = $this->product->create($dataForm);
        //======================================================= IMAGEM INICIO (CADASTRANDO) 
        if($produto){
            
            $images = $request->images;
            
            if($images != null)    //permitindo cadastro de produto mesmo sem imagem
            {
                foreach ($images as $key => $img) {
                   
                    $name = $img->getClientOriginalName();
                    
                    
                     $this->productPhoto->create(array(
                         "image" => $name,
                         "product_id" => $produto->id
                     ));  
                                   
                   
                    $extenstion = $img->extension();  // retorna a extensão do arquivo
                    $nameFile = "{$name}.{$extenstion}";  // juntando o nome com a extensão
                    
                    //public_path($path)
                    //$img->storeAs("produtos/{$produto->id}", $name);  // efetivamente salvar
                    $img->move(public_path("produtos/{$produto->id}"),$name);
               }
            }
            
        //======================================================= IMAGEM FIM (CADASTRANDO)  
            return redirect()->route('produtos.index', ['success' => 'ok'])->with(['success' => 'ok']);
        } else{
            return redirect()->route('produtos.store');
        } 
    }

    
    public function show($id){
        
        //recupera o produto pelo seu id
        $product = $this->product->find($id);
        
        $title = "Produto: {$product->name}";

        $photos = Product::find($id)->photos()->get();
        
        return view('produto/mostrar', compact('product', 'title', 'photos'));
        
    }
    
    public function deleteImage($id, $productId){

        $img = $this->productPhoto->find($id);

        if(!is_null($img)){
            $img->delete();
        }

        return redirect()->route('produtos.edit', array("id" => $productId));
    } 
    
    public function edit($id) {
        
        //recupera o produto pelo seu id
        $product = $this->product->find($id);
        
        $title = 'Editar Produto';

        $photos = Product::find($id)->photos()->get();
        
        return view('produto/criar', compact('title', 'product', 'photos'));
    }

    public function update(ProductFormRequest $request, $id){   // Atualizando o cadastro (Injetado a dependencia do request) 
        
        //Recupera todos os dados do formulário
        $dataForm = $request->all();
        
        //Recupera o item para editar
        $product = $this->product->find($id);
        //===========================================================================IMAGEM INICIO EDITANDO  
        $images = $request->images;

            if($images){   //permite inserir imagem 
            // -------------- inserindo
            foreach ($images as $key => $img) {
                   
                $name = $img->getClientOriginalName();
                
                $this->productPhoto->create(array(
                    "image" => $name,
                    "product_id" => $product->id
                ));  
                                
                
                $extenstion = $img->extension();  // retorna a extensão do arquivo
                $nameFile = "{$name}.{$extenstion}";  // juntando o nome com a extensão

                //$img->storeAs("produtos/{$product->id}", $name);  // efetivamente salvar
                $img->move(public_path("produtos/{$product->id}"),$name);
            }
        }
        //===========================================================================IMAGEM FIM EDITANDO
        //Altera os itens
        $update = $product->update($dataForm);
        
        //Verifica se realmente editou
        if($update){
            return redirect()->route('produtos.index', ['success' => 'ok'])->with(['success' => 'ok']);
        }else{
            return redirect()->route('produtos.edit', $id)->with(['errors' => 'Falha ao editar']);
        }
        
    }

    
    public function destroy($id) {
        
        $product = $this->product->find($id);

        $resultado = \DB::table('itens_pedido')->where('produto_id', '=', $id)->count();
           
        //$delete = $product->delete();       // roda a função static na model Product (static::deleted)
        
        if($resultado == 0){
            $delete = $product->delete(); // roda a função static na model Product (static::deleted)
            return redirect() ->route ('produtos.index');
        }else{
            Session::flash('message', "Erro! O produto não pode ser deletado. Esta vinculado a um pedido.");
            return Redirect::back();
        }

        /* ANTES DA MODIFICAÇÃO DE VALIDAÇÃO
        if ($delete){
            return redirect() ->route ('produtos.index');
        }
        else{
            return redirect() ->route ('produtos.show', $id) -> with (['errors' => 'Falha ao Deletar' ]);
        }
        */
    }


    public function filtro(Request $request, Product $product)   //Product vem da model, função para buscar
    { 
        $dataForm = $request->except('_token');
        
        $produto = isset($dataForm["name"]) ? $dataForm["name"] : "";
        $where   = "products.id > 0";

        if(!empty($produto)){
            $where = "name LIKE '%{$produto}%'";
        }

        $products = $this->product->selectRaw("products.id, name, valor")
                   ->whereRaw($where)
                   ->orderBy("id", "desc")
                   ->paginate($this->totalPorPagina);

        return view('produto/index', compact('products', 'dataForm', 'produto'));
    }

    //busca do produto no pedido
    /*public function buscarProduto(Request $request, Product $product)   //Product vem da model, função para buscar
    {
        $dataForm = $request->except('_token');

        $products = $product->search($dataForm, $this->totalPorPagina);

        return view('pedido/criar', compact('products', 'dataForm'));
    }*/


    public function pedidoMostraProduto($id, $pedidoId)
    {
        $product = $this->product->find($id);

        $pedido = $this->pedido->find($pedidoId);

        $title = "Produto: {$product->name}";

        $photos = Product::find($id)->photos()->get();
        
        return view('produto/mostrarNoPedido', compact('product', 'title', 'photos', 'pedido'));
    }
    
}