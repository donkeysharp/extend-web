@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Datos de Cliente</b></div>
      <div class="panel-body">
      {{Form::open(['url' => '/clients/' . $model->id, 'method' => $model->id ? 'PUT' : 'POST'])}}
        <div class="row">
          <div class="col-md-7">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="text" name="name" class="form-control" placeholder="Nombre" value="{{$model->name}}" />
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="text" name="phone" class="form-control" placeholder="Teléfono" value="{{$model->phone}}" />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="text" name="description" class="form-control" placeholder="Descripción" value="{{$model->description}}" />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-7">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="text" name="address" class="form-control" placeholder="Dirección" value="{{$model->address}}" />
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              {{Form::select('city', $cities, $model->city, [
                'class' => 'form-control'
              ])}}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="text" name="website" class="form-control" placeholder="Página Web" value="{{$model->website}}" />
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
            <a href="/dashboard/clients">Volver</a>&nbsp;
          </div>
        </div>
      {{Form::close()}}
    @if($model->id)
      <div class="section-divider"><span>CONTACTOS</span></div>
      {{Form::open(['url' => '/clients/' . $model->id . '/contacts'])}}
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" name="name" class="form-control" placeholder="Nombre de Contacto" />
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" name="position" class="form-control" placeholder="Cargo" />
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" name="email" class="form-control" placeholder="Email" />
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-user"></i>
              </div>
              <input type="text" name="phone" class="form-control" placeholder="Celular" />
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-info">
            <i class="fa fa-plus"></i>&nbsp;
            Agregar Contacto
          </button>
        </div>
      </div>
      {{Form::close()}}
      <div class="row">
        <div class="col-md-12">
          <table class="table">
            <thead>
              <th class="col-md-3">Nombre</th>
              <th class="col-md-3">Cargo</th>
              <th class="col-md-2">Email</th>
              <th class="col-md-2">Celular</th>
            </thead>
            <tbody>
            @foreach($model->contacts as $contact)
              <tr>
                <td>{{$contact->name}}</td>
                <td>{{$contact->position}}</td>
                <td>{{$contact->email}}</td>
                <td>{{$contact->phone}}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
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
