@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Lista de Temas</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-8"></div>
          <div class="col-md-4">
            <a class="btn btn-light" href="{{url('/dashboard/topics/create')}}">
              <i class="fa fa-plus"></i>&nbsp;
              Adicionar Nuevo Tema
            </a>
          </div>
        </div>
        <table class="table">
          <thead>
            <th class="col-md-1">#</th>
            <th class="col-md-6">Nombre</th>
            <th class="col-md-4">Descripción</th>
            <th class="col-md-1"></th>
          </thead>
          <tbody>
          <?php $i = 1 ?>
          @foreach($topics->getItems() as $topic)
            <tr>
              <td>{{$i}}</td>
              <td>{{$topic->name}}</td>
              <td>{{$topic->description}}</td>
              <td>
                <a href="{{url('dashboard/topics/' . $topic->id . '/edit')}}" class="btn btn-light">
                  <i class="fa fa-pencil"></i>
                </a>
              </td>
            </tr>
            <?php $i++ ?>
          @endforeach
          </tbody>
        </table>
        <center>
          {{Form::paginator($topics, '/dashboard/topics')}}
        </center>
      </div>
    </div>
  </div>
</div>
@stop
