'use strict';
var React = window.React;
var months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];


function getFormattedData(data) {
  var chartRes = [], tableRes = [];

  for (var key in data) {
    chartRes.push([
      key,
      parseInt(data[key].positive, 10),
      parseInt(data[key].negative, 10),
      parseInt(data[key].neutral, 10),
    ]);
    tableRes.push({
      source: key,
      positive: data[key].positive,
      negative: data[key].negative,
      neutral: data[key].neutral
    });
  }

  return {
    chartRes: chartRes,
    tableRes: tableRes
  };
}

function generateReport() {
  var reportData = getFormattedData(this.props.data)

  drawChart.call(this, reportData.chartRes);
  drawTable.call(this, reportData.tableRes);
}

function drawTable(data) {
  var
    table = this.refs.dataTable.getDOMNode(),
    tbody = table.getElementsByTagName('tbody')[0],
    tpl = '';

  for (var i = 0; i < data.length; ++i) {
    tpl += '<tr><td>' + data[i].source + '</td><td>' + data[i].positive + '</td>';
    tpl += '<td>' + data[i].negative + '</td><td>' + data[i].neutral + '</td></tr>';
  }
  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Fuente');
  data.addColumn('number', 'Positivo');
  data.addColumn('number', 'Negativo');
  data.addColumn('number', 'Neutro');
  data.addRows(reportData);

  var month = months[parseInt(this.props.month, 10)];
  var title = 'Tendencia porcentual por fuente ' + month + ' ' + this.props.year;
  var options = {
    title: title,
    width:600,
    height:400,
    legend: { position: 'top', maxLines: 3 },
    bar: { groupWidth: '75%' },
    isStacked: true,
  };

  var el = this.refs.chart.getDOMNode();
  var chart = new google.visualization.ColumnChart(el);
  this.chart = chart;
  chart.draw(data, options);
}

var Report7 = React.createClass({
  componentDidMount: function () {
    // if (this.props.data) {
      generateReport.call(this);
    // }
  },
  getExportData: function() {
    var table = this.refs.dataTable.getDOMNode().innerHTML;
    var image = this.chart.getImageURI();
    // image = '<img src="' + image + '" style="width:600px; height:400px;" />';

    return {
      table: table,
      image: image
    }
  },
  render: function () {
    return (
      <div>
        <div className="row">
          <div className="col-md-4 col-md-offset-4">
            <table ref="dataTable" className="table table-bordered">
              <thead>
                <th>Fuente</th>
                <th>Positivo</th>
                <th>Negativo</th>
                <th>Neutro</th>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <center>
          <div ref="chart"></div>
        </center>
      </div>
    );
  }
});

module.exports = Report7;
