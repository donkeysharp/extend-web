@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Exportar Datos</b></div>
      <div class="panel-body">
      <div class="section-divider"><span>SELECCIONAR CAMPOS A EXPORTAR</span></div>
      {{Form::open()}}
        <div class="form-group">
          <label>
            <input type="checkbox" name="news" value="true" />
            Noticias
          </label>
          &nbsp;&nbsp;<br />
          <label>
            <input type="checkbox" name="news_details" value="true" />
            Detalle de Noticias
          </label>
          &nbsp;&nbsp;<br />
          <label>
            <input type="checkbox" name="clients" value="true" />
            Clientes
          </label>
          &nbsp;&nbsp;<br />
          <label>
            <input type="checkbox" name="media" value="true" />
            Medios
          </label>
          &nbsp;&nbsp;<br />
          <label>
            <input type="checkbox" name="topics" value="true" />
            Temas
          </label>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-light">
            <i class="fa fa-cloud-download"></i>&nbsp;
            Exportar Datos a Excel
          </button>
        </div>
      {{Form::close()}}
      </div>
    </div>
  </div>
</div>
@stop
