@extends('master')

@section('content')
<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Reportes</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Reporte</label>
              <select class="form-control">
                <option value="">--- Seleccione un reporte ---</option>
                <option value="r1">Comparación según medio de comunicación</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Desde</label>
              <input type="text" class="form-control" name="from" id="from" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Hasta</label>
              <input type="text" class="form-control" name="to" id="to" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <button class="btn btn-light">
                <i class="fa fa-bar-chart-o"></i>
                &nbsp;
                Generar Reporte
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
