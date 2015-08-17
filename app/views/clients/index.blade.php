@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Lista de Clientes</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-8"></div>
          <div class="col-md-4">
            <a class="btn btn-light" href="{{url('/dashboard/clients/create')}}">
              <i class="fa fa-plus"></i>&nbsp;
              Adicionar Nuevo Cliente
            </a>
          </div>
        </div>
        <table class="table">
          <thead>
            <th class="col-md-1">#</th>
            <th class="col-md-4">Nombre</th>
            <th class="col-md-4">Ciudad</th>
            <th class="col-md-2">Teléfono</th>
            <th class="col-md-1"></th>
          </thead>
          <tbody>
          <?php $i = 1; ?>
          @foreach($clients->getItems() as $client)
            <tr>
              <td>{{$i}}</td>
              <td>{{$client->name}}</td>
              <td>{{$client->city}}</td>
              <td>{{$client->phone}}</td>
              <td>
                <a href="{{url('dashboard/clients/' . $client->id . '/edit')}}" class="btn btn-light">
                  <i class="fa fa-pencil"></i>
                </a>
              </td>
            </tr>
            <?php $i++ ?>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@stop
