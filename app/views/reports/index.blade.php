@extends('master')

@section('content')
<div id="report-container"></div>
@stop

@section('scripts')
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="{{asset('assets/vendors/js/react.js')}}"></script>
<script src="{{asset('assets/vendors/js/md5.js')}}"></script>
<script src="{{asset('assets/js/build.min.js')}}"></script>
<script type="text/javascript">
  // Load the Visualization API and the piechart package.
  google.load('visualization', '1.0', {'packages':['corechart']});

  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(chartLibraryLoaded);

  function chartLibraryLoaded() {
    console.log('Chart library loaded');

    var el = document.getElementById('report-container');
    var params = {};
    React.render(React.createElement(MyApp.Report, params), el);
  }
</script>
@stop
