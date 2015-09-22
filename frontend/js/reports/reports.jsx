'use strict';
var React = window.React;
var $http = require('../http');
var reportMap = require('./reportMap');

function getTitle(key) {
  if (key.indexOf('tv_') !== -1) {
    return 'Reportes Televisión';
  } else if (key.indexOf('radio_') !== -1) {
    return 'Reportes Radio';
  } else if (key.indexOf('press_') !== -1) {
    return 'Reportes Prensa';
  } else if (key.indexOf('general_') !== -1) {
    return 'Reportes Generales';
  }
  return '';
}

function exportData(e) {
  var newWin = window.open('', 'thePopup', 'width=1000,height=600');
  var tpl = '<html><head><title>Reportes</title>';
  tpl += '<link rel="stylesheet" type="text/css" href="/assets/vendors/css/bootstrap.min.css">';
  tpl += '</head><body>';
  tpl += '<div style="margin-left: 16px; margin-right:16px">';
  tpl += '<div class="row"><div class="col-md-12">';

  var refs = this.refs;
  var lastTitle = '';
  for (var key in refs) {
    if (refs[key].getExportData) {
      console.log(key);
      var currentTitle = getTitle(key);
      if (lastTitle !== currentTitle) {
        tpl += '<h2>' + currentTitle + '</h2>';
        lastTitle = currentTitle;
      }

      var data = refs[key].getExportData();
      tpl += '<div class="row"><div class="col-md-4 col-md-offset-4">';
      tpl += '<table class="table table-bordered">' + data.table + '</table>';
      tpl += '</div></div>';
      tpl += '<center>' + data.image;
      if (data.image2) {
        tpl += '<br>' + data.image2;
      }
      tpl += '</center>';
    }
  }
  tpl += '</div></div></div></body></html>';
  newWin.document.write(tpl);
}

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

function pushReport(data, result, title, type) {
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
      var refKey = type + '_' + key;
      temporal.push(<Report ref={refKey} data={data[key]} />);
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
  var generalData = data.general;

  var result = [];
  var refKeys = [];
  pushReport(generalData, result, 'Reportes Generales', 'general');
  pushReport(pressData, result, 'Reportes Prensa', 'press');
  pushReport(radioData, result, 'Reportes Radio', 'radio');
  pushReport(tvData, result, 'Reportes Televisión', 'tv');

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
    var report = '', exportButton = '';
    if (this.state.displayReport) {
      exportButton = <button ref="export" className="btn btn-success" onClick={exportData.bind(this)}><i className="fa fa-print"></i> Exportar Reporte</button>;
      report = getReports.call(this);
    }
    var clients = getClients.call(this);
    return (
      <div className="row">
        <div className="col-md-10 col-md-offset-1">
          <div className="panel panel-dark">
            <div className="panel-heading"><b>Reportes</b></div>
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
                {exportButton}
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
