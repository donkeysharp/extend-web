@extends('master')

@section('content')
<h1>Noticias</h1>
<ul>
@foreach($news as $item)
<li>{{$item->code}}</li>
@endforeach
</ul>
@stop
