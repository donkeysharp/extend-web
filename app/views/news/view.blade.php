@extends('master')

@section('content')
<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Información de Noticia</b></div>
      <div class="panel-body">
      @if($news->date)
        <b>Fecha: </b> {{$news->date}} &nbsp;&nbsp;
      @endif
      @if($news->client)
        <b>Cliente: </b> {{$news->client->name}} &nbsp;&nbsp;
      @endif
      @if($news->press_note)
        <b>Nota de Prensa: </b> {{$news->press_note}} &nbsp;&nbsp;
      @endif
      @if($news->code)
        <b>Código: </b> {{$news->code}}&nbsp;&nbsp;
      @endif
      @if($news->clasification)
        <b>Clasificación: </b> {{$news->clasification}}&nbsp;&nbsp;
      @endif
        @foreach($news->details as $item)
          @if($item->type == 2)
            <div class="section-divider">
              <span>DIGITAL</span>
            </div>
            <b>Título: </b> {{$item->title}} &nbsp;&nbsp;
            <b>Subtítulo: </b> {{$item->subtitle}} <br>
            @if($item->media)
              <b>Medio: </b> {{$item->media->name}} &nbsp;&nbsp;
            @endif
            @if($item->section)
              <b>Sección: </b> {{$item->section}}&nbsp;&nbsp;
            @endif
            @if($item->page)
              <b>Página: </b> {{$item->page}} <br>
            @endif
            @if($item->web)
              <b>Fuente: </b> <a href="{{$item->web}}" target="_blank">{{$item->web}}</a> <br>
            @endif
            @if($item->gender)
              <b>Género: </b> {{$item->gender}} <br>
            @endif
            <b>Tendencia:</b> {{Form::tendency($item->tendency)}} <br>
            @if($item->topic)
              <b>Tema:</b> {{$item->topic->name}} &nbsp;&nbsp;
            @endif
            @if($item->measure)
              <b>Medida: </b> {{$item->measure}} &nbsp;&nbsp;
            @endif
            @if($item->cost)
              <b>Costo: </b> {{$item->cost}} <br>
            @endif
            @if($item->description)
              <br>
              <b>Descripción:</b><br>
              <p>{{$item->description}}</p>
            @endif
          @endif
        @endforeach
      </div>
    </div>
  </div>
</div>
@stop
