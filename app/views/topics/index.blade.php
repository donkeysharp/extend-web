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
            <th class="col-md-5">Nombre</th>
            <th class="col-md-4">Descripción</th>
            <th class="col-md-1"></th>
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
              <td>
                <a href="javascript:void(0)" class=" btn btn-danger delete" data-id="{{$topic->id}}" title="Eliminar tema">
                  <i class="fa fa-trash"></i>
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

@section('scripts')
<script src="{{asset('assets/vendors/js/react.min.js')}}"></script>
<script src="{{asset('assets/js/build.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $http = MyApp.$http;
  $('.delete').on('click', function(e) {
   if(!confirm('Está seguro que desea eliminar este tema?')) {return;}
    var id = e.currentTarget.dataset.id;

    $http.remove('/topics/' + id).then(function(res) {
      var messages = document.getElementById('messages');
        messages.innerHTML =  '<div class="alert alert-info alert-dismissable">'+
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
        '&times;'+
        '</button>'+
        'Tema eliminada exitosamente.'+
        '</div>';
        setTimeout(function() {
          window.location = '/dashboard/topics/';
        }, 500);
    }, function(err) {})
  });
});
</script>
@stop
