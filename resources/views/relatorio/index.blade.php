@extends('adminlte::page')

@section('content')

<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{$clientes}}</h3>

              <p>Clientes</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="{{ route('relatorios.filtro', 'cliente') }}" class="small-box-footer">
              Filtrar  <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>


        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{$colaboradores}}</h3>

              <p>Colaboradores</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="{{ route('relatorios.filtro', 'colaborador') }}" class="small-box-footer">
              Filtrar  <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>



        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$mesPedidos}}</h3>
              
              <p>Pedidos do Mês</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-cart-outline"></i>
            </div>
            <a href="{{ route('relatorios.filtro', 'pedido') }}" class="small-box-footer">
              Filtrar <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>



        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{$mesVisitas}}</h3>

              <p>Visitas do Mês</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>

            </div>
            <a href="{{ route('relatorios.filtro', 'visita') }}" class="small-box-footer">
              Filtrar <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      </div>

      <!-- =========================================================================================== -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Relatório de recapitulação mensal</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"> 
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>

                <!--
                <div class="info-box bg-blue">
                  <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Vendedor</span>
                    <span class="info-box-number">5,200</span>

                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                          50% Increase in 30 Days
                        </span>
                  </div>
                </div>
                -->

              </div>
            </div>
            
            <div class="box-body" style="">
              <div class="row">
                <div class="col-md-8">
                  <p class="text-center">
                    <strong>Pedidos: Janeiro a Dezembro, 2018</strong>
                  </p>

                  <div class="chart">
                    <canvas id="salesChart" style="height: 250px; width: 514px;" width="514" height="250"></canvas>
                  </div>
                  
                </div>

              </div>
              
            </div>
            
          </div>
          
        </div>
        
      </div> 
  
@endsection