@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Lista de Medios</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-8"></div>
          <div class="col-md-4">
            <a class="btn btn-light" href="{{url('/dashboard/media/create')}}">
              <i class="fa fa-plus"></i>&nbsp;
              Adicionar Nuevo Medio
            </a>
          </div>
        </div>
        <table class="table">
          <thead>
            <th class="col-md-1">#</th>
            <th class="col-md-4">Nombre</th>
            <th class="col-md-3">Ciudad</th>
            <th class="col-md-2">Tipo</th>
            <th class="col-md-1"></th>
            <th class="col-md-1"></th>
          </thead>
          <tbody>
          <?php $i = 1; ?>
          @foreach($media->getItems() as $item)
            <tr>
              <td>{{$i}}</td>
              <td>{{$item->name}}</td>
              <td>{{$item->city}}</td>
              <td>{{$item->type}}</td>
              <td>
                <a href="{{url('dashboard/media/' . $item->id . '/edit')}}" class="btn btn-light">
                  <i class="fa fa-pencil"></i>
                </a>
              </td>
              <td>
                <a class="btn btn-danger">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
            <?php $i++; ?>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@stop
