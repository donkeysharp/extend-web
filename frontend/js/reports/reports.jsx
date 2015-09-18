'use strict';
var React = window.React;
var $http = require('../http');
var reportMap = require('./reportMap');

function onReportchange (e) {
  var value = e.currentTarget.value;
  var state = {
    report: value,
    displayReport: false
  };
  // TODO: add exceptions to display clients dropdown
  this.setState(state);
}

function onGenerateReport (e) {
  this.setState({displayReport: true});
}

function getReport(report) {
  if (reportMap.hasOwnProperty(report)) {
    var Report = reportMap[report];
    return <Report />
  }
  return '';
}

function getClients() {
  var clients = this.state.clients.map(function(item) {
    return <option value={item.id}>{item.name}</option>
  });

  return (
    <div className="row">
      <div className="col-md-12">
        <div className="form-group">
          <label>Cliente</label>
          <select className="form-control" ref="client">
            <option value="">--- Seleccione un Cliente ---</option>
            {clients}
          </select>
        </div>
      </div>
    </div>
  );
}

var ReportView = React.createClass({
  getInitialState: function () {
    return {
      report: '',
      clients: [],
      enableClients: false,
      displayReport: false
    };
  },
  componentDidMount: function () {
    $http.get('/clients').then(function(res) {
      this.setState({ clients: res });
    }.bind(this), function(err) {})
  },
  render: function() {
    var report = '';
    if (this.state.displayReport) {
      report = getReport(this.state.report);
    }
    var clients = '';
    if (this.state.enableClients) {
      clients = getClients.call(this);
    }
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
                      <option value="Report2">Comparación según tema</option>
                    </select>
                  </div>
                </div>
              </div>
              {clients}
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
                    <button className="btn btn-light" onClick={onGenerateReport.bind(this)}>
                      <i className="fa fa-bar-chart-o"></i>
                      &nbsp;
                      Generar Reporte
                    </button>
                  </div>
                </div>
              </div>
              {report}
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = ReportView;
