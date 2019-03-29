<?php

use App\Models\Product;
use Illuminate\Support\Facades\Storage;


route::group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    // Rota HOME [Tela inicial]
    Route::get('admin', 'AuxController@index');
    //Rota de login
    Route::get('/', 'AuxController@index');
    
    // Rotas de FILTROS
    $this->any('admin/produtos/filtro', 'ProdutoController@filtro')->name('produto.filtro');
    $this->any('admin/user/filtro', 'UserController@filtro')->name('user.filtro');
    $this->any('admin/cidades/filtro', 'CidadeController@filtro')->name('cidade.filtro');
    $this->any('admin/pedido/filtro', 'PedidoController@filtros')->name('pedido.filtros');
    // Rotas de filtro para abrir um pedido
    $this->any('admin/pedido/filtroCliente', 'UserController@buscarClientePedido')->name('filtroUP.buscar');
    $this->any('admin/produtos/filtroProduto', 'PedidoController@filtroPedidoProduto')->name('filtroPP.buscar');

    Route::get('admin/pedido/selecionarCliente/{id}', 'PedidoController@getClienteId')->name('seleciona.getCliente');

    // Rota de CADASTRO PESSOA escolha de tipo de cadastro [Cliente ou Colaborador]
    Route::get('escolha', 'AuxController@cadUserEscolha')->name('escolha.cadUserEscolha');

    //Rotas para excluir
    //Route::post('admin/pedidos/item/{id}', 'PedidoController@deletePedidoItem')->name('pedido.deleteItem');
    //$this->any('admin/pedido/filtroID', 'PedidoController@buscar')->name('filtroID.buscar');

    // Rota para mostrar os Pedidos dentro do menu Gerenciar
    Route::get('admin/pedidos/produtos-lista-add', 'PedidoController@pedidoListaItens')->name('pedido.produtosListaAdd');
    Route::get('admin/mostraPedidos', 'PedidoController@mostrarPedidos')->name('pedido.mostrar');
    Route::post('admin/pedidos/create-pedido-item', 'PedidoController@createItemPedido')->name('pedido.createItem');
    
   
    Route::any('admin/pedido/consultaUser/{id}', 'UserController@pedidoMostraCliente')->name('consulta.pedidoMostraCliente');
    Route::any('admin/pedido/consultaProduto/{id}/{ped_id}', 'ProdutoController@pedidoMostraProduto')->name('consulta.pedidoMostraProduto');
    Route::any('admin/pedido/add/{id}/{ped_id}', 'PedidoController@addProduto')->name('add.addProduto');
    Route::get('admin/aux/view', 'AuxController@formView');
    Route::post('admin/produtos/image/{id}/{product_id}', 'ProdutoController@deleteImage');
    Route::get('admin/cidades/combo', 'CidadeController@combo');
    Route::any('admin/aux/deletarItem/{id}', 'AuxController@deleteItemPedido')->name('aux.deletarItemPedido');
    //adicionando item no pedido pelo editar (gerenciar)
    Route::get('admin/pedidos/addItemPedido/{id}', 'PedidoController@addItemPedido')->name('pedidos.addItemPedido');
    //cancelar um pedido
    Route::get('admin/pedidos/cancelarPedido/{id}', 'PedidoController@cancelarPedido')->name('pedidos.cancelarPedido');
    //voltar quando esta editando um pedido
    Route::get('admin/pedidos/voltar/{id}', 'PedidoController@voltarDePedidos')->name('pedidos.voltar');
    //encerrar pedido
    Route::get('admin/pedidos/encerrar/{id}', 'PedidoController@encerrarPedido')->name('pedidos.encerrarPedido');
    //manutenção
    Route::get('admin/manutencao', 'AuxController@emManutencao')->name('aux.emManutencao');
    //conferindo o pedido
    Route::get('admin/aux/conferir/{id}', 'AuxController@conferiPedido')->name('aux.conferiPedido');
    //voltar para home
    Route::get('admin/pedidos/voltarHome/{id}', 'PedidoController@voltarHome')->name('pedidos.voltarHome');
    //validar pedido
    Route::get('admin/pedidos/validar/{id}', 'AuxController@validarPedido')->name('aux.validarPedido');

    //Relatórios
    //ir para o filtrar de relatórios
    Route::get('admin/relatorios/filtrar/{info}', 'RelatorioController@filtroGeral')->name('relatorios.filtro');
    //ir para relatório gerado
    Route::any('admin/relatorios/gerado/{info}', 'RelatorioController@relatorioGerado')->name('relatorios.gerado');
    Route::any('admin/relatorios/filtro/{info}', 'RelatorioController@filtro')->name('relatorios.filtroNome');
    // rota para o PDF Cliente
    Route::get('pdf/cliente/{id}', 'RelatorioController@clientePDF')->name('relatorio.pdfCliente');
    // rota para o PDF Colaborador
    Route::get('pdf/colaborador/{id}', 'RelatorioController@colaboradorPDF')->name('relatorio.pdfColaborador');
    // rota para o PDF Pedidos
    Route::get('pdf', 'RelatorioController@pedidosPDF')->name('relatorio.pdfPedidos');
    // rota para o PDF Pedido em pedido
    Route::get('pdf/pedido/{id}', 'RelatorioController@pedidoPDF')->name('relatorio.pdfPedido');
    Route::get('pdf/visita/{id}', 'RelatorioController@visitaPDF')->name('relatorio.pdfVisita');

    //Acesso a Home do Vendedor
    Route::get('admin/home/vendedor', 'AuxController@homeVendedor')->name('home.vendedor');
    //Configurar lista de visita
    Route::get('admin/home/vendedor/conf', 'AuxController@confVisita')->name('conf.visita');
    //Configurar lista de visita Filtro de cidades
    Route::any('admin/home/vendedor/conf/filtro', 'AuxController@filtroCidadeVisitas')->name('filtro.confCid');
    //Add na lista
    Route::any('admin/home/vendedor/conf/add', 'AuxController@addListaVisita')->name('add.lista');
    //Remover da lista
    Route::any('admin/home/vendedor/remove/{id}', 'AuxController@removeListaVisita')->name('remove.lista');
    //Registrando Visita
    Route::any('admin/home/vendedor/registra', 'AuxController@registraVisita')->name('registra.visita');


    //Rotas Resource
    Route::resource('admin/produtos', 'ProdutoController');
    Route::resource('admin/user', 'UserController');
    Route::resource('admin/cidades', 'CidadeController');
    Route::resource('admin/pedidos', 'PedidoController');
    Route::resource('admin/aux', 'AuxController');
    Route::resource('admin/relatorios', 'RelatorioController');
});


//------------------------------------------------------------------
/*Route::get('/', 'SiteController@index')->name('home');

Route::get("/testar",function(){

  var_dump($pedido);

});*/

Route::get('/teste', function(){
    
    
    $data = RelatorioService::quantidadePedidosMesAnoAtual();

    return response()->json($data);

});


Auth::routes();