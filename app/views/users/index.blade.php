@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Lista de Usuarios</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-8"></div>
          <div class="col-md-4">
            <a class="btn btn-light" href="{{url('/dashboard/users/create')}}">
              <i class="fa fa-plus"></i>&nbsp;
              Adicionar Nuevo Usuario
            </a>
          </div>
        </div>
        <table class="table">
          <thead>
            <th class="col-md-1">#</th>
            <th class="col-md-2">Username</th>
            <th class="col-md-5">Nombre</th>
            <th class="col-md-3">Email</th>
            <th class="col-md-1"></th>
          </thead>
          <tbody>
          <?php $i = 1; ?>
          @foreach($users->getItems() as $user)
            <tr>
              <td>{{$i}}</td>
              <td>{{$user->username}}</td>
              <td>{{$user->name}}</td>
              <td>{{$user->email}}</td>
              <td>
                <a href="{{url('dashboard/users/' . $user->id . '/edit')}}" class="btn btn-light">
                  <i class="fa fa-pencil"></i>
                </a>
              </td>
            </tr>
            <?php $i++ ?>
          @endforeach
          </tbody>
        </table>
        <center>
          {{Form::paginator($users, '/dashboard/users')}}
        </center>
      </div>
    </div>
  </div>
</div>
@stop
