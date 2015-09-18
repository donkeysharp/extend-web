'use strict';
var React = window.React;

var reportMap = require('./reportMap');

function onReportchange (e) {
  var value = e.currentTarget.value;
  this.setState({ report: value });
}

var ReportView = React.createClass({
  getInitialState: function () {
    return {
      report: ''
    };
  },
  render: function() {
    return (
      <div className="row">
        <div className="col-md-10 col-md-offset-1">
          <div className="panel panel-dark">
            <div className="panel-heading"><b>asdasdasdasd</b></div>
            <div className="panel-body">
              <div className="row">
                <div className="col-md-12">
                  <div className="form-group">
                    <label>Reporte</label>
                    <select className="form-control" value={this.state.report} onChange={onReportchange.bind(this)}>
                      <option value="">--- Seleccione un reporte ---</option>
                      <option value="Report1">Comparación según medio de comunicación</option>
                    </select>
                  </div>
                </div>
              </div>
              <div className="row">
                <div className="col-md-6">
                  <div className="form-group">
                    <label>Desde</label>
                    <input type="text" className="form-control" name="from" id="from" />
                  </div>
                </div>
                <div className="col-md-6">
                  <div className="form-group">
                    <label>Hasta</label>
                    <input type="text" className="form-control" name="to" id="to" />
                  </div>
                </div>
              </div>
              <div className="row">
                <div className="col-md-6">
                  <div className="form-group">
                    <button className="btn btn-light">
                      <i className="fa fa-bar-chart-o"></i>
                      &nbsp;
                      Generar Reporte
                    </button>
                  </div>
                </div>
              </div>
              <br />
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = ReportView;
