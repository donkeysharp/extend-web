@extends('master')

@section('content')
<div id="news-edit-form"></div>
@stop

@section('scripts')
<script src="{{asset('assets/vendors/js/react.min.js')}}"></script>
<script src="{{asset('assets/js/build.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
  var el = document.getElementById('news-edit-form');
  React.render(React.createElement(MyApp.NewsEditor, null), el);
});
</script>
@stop

