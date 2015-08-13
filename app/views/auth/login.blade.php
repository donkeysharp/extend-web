<!doctype html>
<html>
<head>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>Extends | Login</title>
@if (App::environment('local'))
  <link rel="stylesheet" href="{{asset('assets/vendors/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/css/font-awesome.min.css')}}">
@else
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"
    rel="stylesheet">
  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"
    rel="stylesheet">
@endif
  <link rel="stylesheet" href="{{asset('assets/css/main.min.css')}}">
</head>
<body>

<div id="wrapper">
<div class="row">
  <div class="col-md-4 col-md-offset-2">
    <div class="form-group">
    {{Form::open()}}
      {{Form::text('username',Input::old('username'), [
        'class'=>'form-control',
        'placeholder'=>'Username',
        'autocomplete'=>'off'
      ])}}
      {{Form::password('password', [
        'class'=>'form-control',
        'placeholder'=>'Password',
        'autocomplete'=>'off',
      ])}}
      {{Form::submit('Log In', [
        'class'=>'btn btn-light btn-block'
      ])}}
      {{Form::close()}}
    </div>
  </div>

</div>

</div>
<div class="message-log" id="messages" my-messages>
@if($errors->has())
  <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
      &times;
    </button>
    @foreach($errors->all() as $error)
      {{ $error }}<br>
    @endforeach
  </div>
@endif
@if(Session::has('error'))
  <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
      &times;
    </button>
    {{Session::get('error')}}
  </div>
@endif
@if(Session::has('message'))
  <div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
      &times;
    </button>
    {{Session::get('message')}}
  </div>
@endif
</div>

@if (App::environment('local'))
<script src="{{asset('assets/vendors/js/jquery.min.js')}}"></script>
@else
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
@endif
<script src="{{asset('assets/vendors/js/bootstrap.min.js')}}"></script>
<script>
  $(document).ready(function() {
    $(document.getElementsByName('username')[0]).focus();
  });
</script>
</body>
</html>
