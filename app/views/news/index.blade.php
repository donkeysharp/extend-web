@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Lista de Noticias</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-8"></div>
          <div class="col-md-4">
            <a class="btn btn-light" href="{{url('/dashboard/news/create')}}">
              <i class="fa fa-plus"></i>&nbsp;
              Adicionar Nueva Noticia
            </a>
          </div>
        </div>
        <table class="table">
          <thead>
            <th class="col-md-1">Fecha</th>
            <th class="col-md-2">Cliente</th>
            <th class="col-md-2">Medio</th>
            <th class="col-md-5">Título</th>
            <th class="col-md-1">Tendencia</th>
            <th class="col-md-1"></th>
          </thead>
          <tbody>
          <?php $i = 1; ?>
          @foreach($news->getItems() as $item)
            <tr>
              <td>{{$i}}</td>
              <td>
              @if(isset($item->client))
              {{$item->client->name}}
              @endif
              </td>
              <td>
              @if(count($item->details)>0)
                {{$item->details[0]->media->name}}
              @endif
              </td>
              <td>{{$item->press_note}}</td>
              <td>
                @if(count($item->details)>0)
                  {{Form::tendency($item->details[0]->tendency)}}
                @endif
              </td>
              <td>
                <a href="{{url('dashboard/news/' . $item->id . '/edit')}}" class="btn btn-light">
                  <i class="fa fa-pencil"></i>
                </a>
              </td>
            </tr>
            <?php $i++ ?>
          @endforeach
          </tbody>
        </table>
        <center>
          {{Form::paginator($news, '/dashboard/news')}}
        </center>
      </div>
    </div>
  </div>
</div>
@stop
