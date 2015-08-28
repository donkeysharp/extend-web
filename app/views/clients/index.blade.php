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
            <th class="col-md-1">Teléfono</th>
            <th class="col-md-1"></th>
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
              <td>
                <a href="javascript:void(0)" class=" btn btn-danger delete" data-id="{{$client->id}}" title="Eliminar Cliente de Noticia">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
            <?php $i++ ?>
          @endforeach
          </tbody>
        </table>
        <center>
          {{Form::paginator($clients, '/dashboard/clients')}}
        </center>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
<script src="{{asset('assets/vendors/js/react.min.js')}}"></script>
<script src="{{asset('assets/js/build.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $http = MyApp.$http;
  $('.delete').on('click', function(e) {
   if(!confirm('Está seguro que desea eliminar este cliente?')) {return;}
    var id = e.currentTarget.dataset.id;

    $http.remove('/clients/' + id).then(function(res) {
      var messages = document.getElementById('messages');
        messages.innerHTML =  '<div class="alert alert-info alert-dismissable">'+
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
        '&times;'+
        '</button>'+
        'Cliente eliminado exitosamente.'+
        '</div>';
        setTimeout(function() {
          window.location = '/dashboard/clients/';
        }, 500);
    }, function(err) {})
  });
});
</script>
@stop
