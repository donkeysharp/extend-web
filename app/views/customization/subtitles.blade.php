@extends('master')

@section('content')
<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Personalizar orden de subtítulos</b></div>
      <div class="panel-body">
        Indique el orden de aparición de los subtítulos
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              {{Form::select('client_id', $clients, null, [
                'class' => 'form-control',
                'id' => 'client_id'
              ])}}
            </div>
          </div>
          <div class="col-md-4">
            <button class="btn btn-light" id="btn-save">
              <i class="fa fa-save"></i>
              &nbsp;&nbsp;
              Guardar Cambios
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col-md-5 col-md-offset-4">
            <ul id="items" class="sortable-list">
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
<script src="{{asset('assets/vendors/js/sortable.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/react.min.js')}}"></script>
<script src="{{asset('assets/js/build.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $http = MyApp.$http;

    var el = document.getElementById('items');
    var sortable = Sortable.create(el, { animation: 150, scroll: true });

    $('#client_id').on('change', function(e) {
      var clientId = e.currentTarget.value;

      $http.get('/custom/subtitles/' + clientId).then(function(res) {
        var tpl = '';
        res = res.map(function(item) {
          return '<li data-subtitle-id="' + item.id + '"><i class="fa fa-arrows"></i>' +
                '&nbsp;&nbsp;' +
                item.subtitle +
              '</li>';
        });
        el.innerHTML = res.join('');
      });
    });
    $('#btn-save').on('click', function(e) {
      var ids = $('#items').find('li').map(function(idx, item) {
        return item.dataset.subtitleId
      });
      var res = [];
      for (var i = 0; i < ids.length; ++i) {
        if (typeof ids[i] === 'string') {
          res.push(ids[i]);
        }
      };
      var clientId = document.getElementById('client_id').value;
      $http.post('/custom/subtitles/' + clientId, {
        subtitles: res
      }).then(function(res) {
        var tpl = '';
        tpl = '<div class="alert alert-success alert-dismissable">' +
              '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">' +
              '&times;' +
              '</button>' +
              'Cambios guardados con éxito' +
              '</div>';
        $('#messages').append(tpl);
      });
    });

  });
</script>
@stop
