@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Datos de Usuario</b></div>
      <div class="panel-body">
      {{Form::open(['url' => '/users/' . $model->id, 'method' => $model->id ? 'PUT' : 'POST'])}}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="username">Username</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="text" name="username" class="form-control" placeholder="Username" value="{{$model->username}}" />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name">Nombre</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="text" name="name" class="form-control" placeholder="Nombre" value="{{$model->name}}" />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="email">Email</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="text" name="email" class="form-control" placeholder="Email" value="{{$model->email}}" />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="password">Contraseña</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" value="" />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="confirm">Confirmar Contraseña</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="password" name="confirm" class="form-control" placeholder="Confirmar Contraseña" value="" />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-7">
            <button type="submit" class="btn btn-light">
              <i class="fa fa-save"></i>&nbsp;
              Guardar Cambios
            </button>
          @if($model->id)
            <button type="button" class="btn btn-danger" data-id="{{$model->id}}" id="btn-delete">
              <i class="fa fa-trash"></i>&nbsp;
              Eliminar Cliente
            </button>
          @endif
            <a href="/dashboard/users">Volver</a>&nbsp;
          </div>
        </div>
      {{Form::close()}}
      </div>
    </div>
  </div>
</div>
@stop
@if($model->id)
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
  $('#btn-delete').on('click', function(e) {
    if(!confirm('Esta seguro que desea eliminar este elemento?')) {return;}
    var clientId = e.currentTarget.dataset.id;
    $.ajax('/clients/' + clientId, {
      type: 'DELETE',
    }).then(function(res){
      $('#messages').html(' <div class="alert alert-info alert-dismissable">'+
    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
      '&times;'+
    '</button>'+
    'Cliente eliminado exitosamente.'+
  '</div>');
      setTimeout(function() {
        window.location = '/dashboard/clients';
      }, 500);
    }, function(err) {});
    console.log(clientId);
  });
});
</script>
@endif
@stop
