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
  var newWin = window.open('', 'thePopup', 'width=1000,height=600,scrollbars=yes');
  var tpl = '<html><head><title>Reportes</title>';
  tpl += '<link rel="stylesheet" type="text/css" href="/assets/vendors/css/bootstrap.min.css">';
  tpl += '<style type="text/css">';
  tpl += '.table-bordered td {padding: 5px} .table-bordered th {padding: 5px}';
  tpl += '</style>';
  tpl += '</head><body style="font-family:sans-serif; color:#1D1D1D; padding: 30px;">';

  var refs = this.refs;
  var lastTitle = '';
  for (var key in refs) {
    if (refs[key].getExportData) {
      var currentTitle = getTitle(key);
      if (lastTitle !== currentTitle) {
        tpl += '<h2>' + currentTitle + '</h2>';
        lastTitle = currentTitle;
      }

      var data = refs[key].getExportData();
      tpl += '<table class="table-bordered">' + data.table + '</table>';
      tpl += '<br>';
      tpl += '<table><tr><td>'
      tpl += data.image;
      if (data.image2) {
        tpl += '<br>';
        tpl += data.image2;
      }
      tpl += '</td></tr></table>';
    }
  }
  tpl += '</body></html>';
  newWin.document.write(tpl);
}

function exportData2() {
  var refs = this.refs, reportData, key, currentTitle, lastTitle = '';

  refs.exportButton.getDOMNode().disabled = true;
  refs.exportSpinner.getDOMNode().setAttribute('class', 'fa fa-spin fa-spinner');

  var data = {};
  for (key in refs) {
    if (refs[key].getExportData) {
      data[key] = {};
      currentTitle = getTitle(key);

      if (lastTitle !== currentTitle) {
        data[key].subtitle = currentTitle;
        lastTitle = currentTitle;
      }

      reportData = refs[key].getExportData();
      data[key].table = reportData.table;
      data[key].image = reportData.image;
      if (reportData.image2) {
        data[key].image2 = reportData.image2;
      }
    }
  }

  // var dataHashSum = CryptoJS.MD5(JSON.stringify(data)).toString();

  // $http.get('/reports/export/check', { md5: dataHashSum }).then(function(res) {
  //   if (res.reportExist) {
  //     data = { md5: dataHashSum };
  //   } else {
  //     data.checksum = dataHashSum;
  //   }

  //   $http.post('/reports/export', data).then(function(res) {
  //     refs.exportButton.getDOMNode().removeAttribute('disabled');
  //     refs.exportSpinner.getDOMNode().setAttribute('class', 'fa fa-print');
  //     window.location = '/' + res.filename;
  //   });
  // });

  $http.post('/reports/export', data).then(function(res) {
    refs.exportButton.getDOMNode().removeAttribute('disabled');
    refs.exportSpinner.getDOMNode().setAttribute('class', 'fa fa-print');
    window.location = '/' + res.filename;
  });
}

function onGenerateReport (e) {
  this.setState({displayReport: false});
  var data = {
    client_id: this.refs.client.getDOMNode().value,
    month: this.refs.month.getDOMNode().value,
    year: this.refs.year.getDOMNode().value,
    clasification: this.refs.clasification.getDOMNode().value,
  };
  $http.get('/reports', data).then(function(res) {
    this.setState({data: res, displayReport: true});
  }.bind(this));
}

function pushReport(data, result, title, type, month, year) {
  var temporal = [];
  for (var key in data) {
    if (reportMap.hasOwnProperty(key)) {
      // if (Object.prototype.toString.call(data[key]) === '[object Array]') {
      //   if (data[key].length <= 0) { continue; }
      // }
      // if (key === 'Report3') {
      //   if (data[key].positive === '0' && data[key].negative === '0' && data[key].neutral === '0') {
      //     continue;
      //   }
      // }
      var Report = reportMap[key];
      var refKey = type + '_' + key;
      temporal.push(<Report ref={refKey} data={data[key]} reportName={key} type={type} month={month} year={year} />);
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
  var month = this.refs.month.getDOMNode().value;
  var year = this.refs.year.getDOMNode().value;

  var result = [];
  var refKeys = [];
  pushReport(generalData, result, 'Reportes Generales', 'general', month, year);
  pushReport(pressData, result, 'Reportes Prensa', 'press', month, year);
  pushReport(radioData, result, 'Reportes Radio', 'radio', month, year);
  pushReport(tvData, result, 'Reportes Televisión', 'tv', month, year);

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
      exportButton = <button ref="exportButton" className="btn btn-success"
        onClick={exportData2.bind(this)}>
          <i ref="exportSpinner" className="fa fa-print"></i> Exportar Reporte
        </button>;
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
                <div className="col-md-3">
                  <div className="form-group">
                    <label>Año</label>
                    <input type="text" className="form-control" ref="year" />
                  </div>
                </div>
                <div className="col-md-3">
                  <div className="form-group">
                    <label>Clasificación</label>
                    <select ref="clasification" className="form-control">
                      <option value="B">B</option>
                      <option value="A">A</option>
                    </select>
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
