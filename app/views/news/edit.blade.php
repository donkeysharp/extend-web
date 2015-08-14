@extends('master')

@section('content')
<input type="hidden" value="{{$id}}" id="__newsId" />
<div id="news-edit-form"></div>
@stop

@section('scripts')
<script src="{{asset('assets/vendors/js/react.min.js')}}"></script>
<script src="{{asset('assets/js/build.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
  var el = document.getElementById('news-edit-form');
  var params = null, newsId;
  if(newsId = document.getElementById('__newsId').value) {
    params = { id: newsId };
  }

  React.render(React.createElement(MyApp.NewsEditor, params), el);
});
</script>
@stop

