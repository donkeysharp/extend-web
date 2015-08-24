@extends('master')

@section('content')
<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Lista de Noticias</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4">
            <a class="btn btn-light" href="{{url('/dashboard/news/create')}}">
              <i class="fa fa-plus"></i>&nbsp;
              Adicionar Nueva Noticia
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

          </div>
        </div>
        <table class="table">
          <thead>
            <th class="col-md-1">Fecha</th>
            <th class="col-md-1">Cliente</th>
            <th class="col-md-2">Medio</th>
            <th class="col-md-5">Título</th>
            <th class="col-md-1">Tendencia</th>
            <th class="col-md-1"></th>
            <th class="col-md-1"></th>
            <th class="col-md-1"></th>
          </thead>
          <tbody>
        @foreach($news->getItems() as $item)
          @foreach($item->details as $detail)
            <tr>
              <td>{{$item->date}}</td>
              <td>
              @if(isset($item->client))
              {{$item->client->name}}
              @endif
              </td>
              <td>
                {{$detail->media->name}}
              </td>
              <td>{{$detail->title}}</td>
              <td>
                {{Form::tendency($detail->tendency)}}
              </td>
              <td>
                <a href="{{url('dashboard/news/'.$item->id.'/view')}}" class="btn btn-success" title="Ver Noticia">
                  <i class="fa fa-eye"></i>
                </a>
              </td>
              <td>
                <a href="{{url('dashboard/news/' . $item->id . '/edit')}}" class="btn btn-light" title="Editar Noticia">
                  <i class="fa fa-pencil"></i>
                </a>
              </td>
              <td>
                <a href="javascript:void(0)" class=" btn btn-danger delete" data-id="-1" title="Eliminar Noticia">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
          @endforeach
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
