'use strict';
var React = window.React;
var $http = require('../http');
var reportMap = require('./reportMap');

function onGenerateReport (e) {
  this.setState({displayReport: false});
  var data = {
    client_id: this.refs.client.getDOMNode().value,
    month: this.refs.month.getDOMNode().value,
    year: this.refs.year.getDOMNode().value
  };
  $http.get('/reports', data).then(function(res) {
    this.setState({data: res, displayReport: true});
  }.bind(this));
}

function pushReport(data, result, title) {
  var temporal = [];
  for (var key in data) {
    if (reportMap.hasOwnProperty(key)) {
      if (Object.prototype.toString.call(data[key]) === '[object Array]') {
        if (data[key].length <= 0) { continue; }
      }
      if (key === 'Report3') {
        if (data[key].positive === '0' && data[key].negative === '0' && data[key].neutral === '0') {
          continue;
        }
      }
      var Report = reportMap[key];
      temporal.push(<Report data={data[key]} />);
    }
  }

  if (temporal.length > 0) {
    result.push(<h2>{title}</h2>)
    temporal.map(function(item) {
      result.push(item);
    });
  }
}

function getReports() {
  var data = this.state.data;
  var pressData = data.press;
  var radioData = data.radio;
  var tvData = data.tv;

  var result = [];
  pushReport(pressData, result, 'Prensa');
  pushReport(radioData, result, 'Radio');
  pushReport(tvData, result, 'TV');

  return result;
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
      clients: [],
      displayReport: false,
      data: {}
    };
  },
  componentDidMount: function () {
    var now = new Date();
    this.refs.month.getDOMNode().value = now.getMonth() + 1;
    this.refs.year.getDOMNode().value = now.getFullYear();
    $http.get('/clients').then(function(res) {
      this.setState({ clients: res });
    }.bind(this), function(err) {})
  },
  render: function() {
    var report = '';
    if (this.state.displayReport) {
      report = getReports.call(this);
    }
    var clients = getClients.call(this);
    return (
      <div className="row">
        <div className="col-md-10 col-md-offset-1">
          <div className="panel panel-dark">
            <div className="panel-heading"><b>asdasdasdasd</b></div>
            <div className="panel-body">
              {clients}
              <div className="row">
                <div className="col-md-6">
                  <div className="form-group">
                    <label>Mes</label>
                    <select className="form-control" ref="month">
                      <option value="1">Enero</option>
                      <option value="2">Febrero</option>
                      <option value="3">Marzo</option>
                      <option value="4">Abril</option>
                      <option value="5">Mayo</option>
                      <option value="6">Junio</option>
                      <option value="7">Julio</option>
                      <option value="8">Agosto</option>
                      <option value="9">Septiembre</option>
                      <option value="10">Octubre</option>
                      <option value="11">Noviembre</option>
                      <option value="12">Diciembre</option>
                    </select>
                  </div>
                </div>
                <div className="col-md-6">
                  <div className="form-group">
                    <label>Año</label>
                    <input type="text" className="form-control" ref="year" />
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
              <div className="report-container">
                {report}
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = ReportView;
