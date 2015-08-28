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
            <th class="col-md-3">Nombre</th>
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
              <td>{{Form::mediaType($item->type)}}</td>
              <td>
                <a href="{{url('dashboard/media/' . $item->id . '/edit')}}" class="btn btn-light">
                  <i class="fa fa-pencil"></i>
                </a>
              </td>
              <td>
                <a href="javascript:void(0)" class=" btn btn-danger delete" data-id="{{$item->id}}" title="Eliminar Cliente de Noticia">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
            <?php $i++; ?>
          @endforeach
          </tbody>
        </table>
        <center>
          {{Form::paginator($media, '/dashboard/media')}}
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

    $http.remove('/media/' + id).then(function(res) {
      var messages = document.getElementById('messages');
        messages.innerHTML =  '<div class="alert alert-info alert-dismissable">'+
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
        '&times;'+
        '</button>'+
        'Medio eliminado exitosamente.'+
        '</div>';
        setTimeout(function() {
          window.location = '/dashboard/media/';
        }, 500);
    }, function(err) {})
  });
});
</script>
@stop
