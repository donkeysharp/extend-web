@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Lista de Boletines</b></div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <th class="col-md-3">Fecha de Creación</th>
            <th class="col-md-4">Cliente</th>
            <th class="col-md-2"># de Noticias</th>
            <th class="col-md-1"></th>
            <th class="col-md-1"></th>
            <th class="col-md-1"></th>
            <th class="col-md-1"></th>
          </thead>
          <tbody>
          @foreach($bulletins->getItems() as $item)
            <tr>
              <td>{{$item->created_at}}</td>
              <td>
              @if($item->client)
                {{$item->client->name}}
              @else
                No hay un cliente definido
              @endif
              </td>
              <td>
                {{count($item->details)}}
              </td>
              <td>
                <a href="/public/bulletins/{{$item->id}}" class="btn btn-success" title="Ver Boletín" target="_blank">
                  <i class="fa fa-eye"></i>
                </a>
              </td>
              <td>
                <a href="javascript:void(0)" class="btn btn-light send" data-id="{{$item->id}}" title="Enviar Boletín">
                  <i class="fa fa-envelope"></i>
                </a>
              </td>
              <td>
                <a href="javascript:void(0)" class="btn btn-info test-send" data-id="{{$item->id}}" title="Envio de prueba">
                  Prueba
                </a>
              </td>
              <td>
                <a href="javascript:void(0)" class="btn btn-danger delete" data-id="{{$item->id}}" title="Eliminar Boletin">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
        <center>
          {{Form::paginator($bulletins, '/dashboard/bulletins')}}
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
    if(!confirm('Está seguro que desea eliminar este boletín?')) {return;}
    var bulletinId = e.currentTarget.dataset.id;

    $http.remove('/bulletins/' + bulletinId).then(function(res) {
      var messages = document.getElementById('messages');
        messages.innerHTML =  '<div class="alert alert-info alert-dismissable">'+
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
        '&times;'+
        '</button>'+
        'Boletín eliminado exitosamente.'+
        '</div>';
        setTimeout(function() {
          window.location = '/dashboard/bulletins/';
        }, 500);
    }, function(err) {})
  });
  $('.send').on('click', function(e) {
    if(!confirm('Está seguro que desea enviar este boletín?')) {return;}
    var bulletinId = e.currentTarget.dataset.id;
    $http.post('/bulletins/' + bulletinId + '/send').then(function(res) {
      var messages = document.getElementById('messages');
        messages.innerHTML =  '<div class="alert alert-success alert-dismissable">'+
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
        '&times;'+
        '</button>'+
        'Boletín enviado exitosamente.'+
        '</div>';
    }, function(err) {})
  });
  $('.test-send').on('click', function(e) {
    if(!confirm('Está seguro que desea realizar los envíos de prueba?')) {return;}
    var bulletinId = e.currentTarget.dataset.id;

    $http.post('/bulletins/' + bulletinId + '/send/test').then(function(res) {
      var messages = document.getElementById('messages');
        messages.innerHTML =  '<div class="alert alert-success alert-dismissable">'+
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
        '&times;'+
        '</button>'+
        'Prueba de boletín enviada exitosamente.'+
        '</div>';
    }, function(err) {})
  });
});
</script>
@stop
