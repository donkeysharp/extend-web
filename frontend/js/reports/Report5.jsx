'use strict';
var React = window.React;
var months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];


function getFormattedData(array) {
  array.sort(function(a, b) {
    a = parseInt(a.news, 10);
    b = parseInt(b.news, 10);
    if (a < b) { return 1; }
    if (a > b) { return -1; }
    return 0;
  });

  var chartRes = [], tableRes = [], i;
  if (array.length > 5) {
    for (i = 0; i < 5; ++i) {
      chartRes.push([array[i].source, parseInt(array[i].news, 10)]);
      tableRes.push({source: array[i].source, news: parseInt(array[i].news, 10)});
    }
    var othersTotal = 0;
    for (i = 5 ; i < array.length; ++i) {
      othersTotal += parseInt(array[i].news, 10);
    }
    chartRes.push(['Otros', othersTotal]);
    tableRes.push({source: 'Otros', news: othersTotal});
  } else {
    for (i = 0; i < array.length; ++i) {
      chartRes.push([array[i].source, parseInt(array[i].news, 10)]);
      tableRes.push({source: array[i].source, news: parseInt(array[i].news, 10)});
    }
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
  var table = this.refs.dataTable.getDOMNode(),
    tbody = table.getElementsByTagName('tbody')[0];
  var tpl = '';
  for (var i = 0; i < data.length; ++i) {
    tpl += '<tr><td>' + data[i].source + '</td><td>' + data[i].news + '</td></tr>';
  }
  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Fuente');
  data.addColumn('number', 'Noticias');
  data.addRows(reportData);

  var month = months[parseInt(this.props.month, 10)];
  var title = 'Comparación según fuente ' + month + ' ' + this.props.year;
  var options = {
    title: title,
    width:600,
    height:400,
    is3D: true,
    pieSliceTextStyle: {
      fontSize: 10
    },
  };

  var el = this.refs.chart.getDOMNode();
  var chart = new google.visualization.PieChart(el);
  this.chart = chart;
  chart.draw(data, options);
}

var Report5 = React.createClass({
  displayName: 'Report5',
  componentDidMount: function () {
    // if (this.props.data && this.props.data.length > 0) {
      generateReport.call(this);
    // }
  },
  getExportData: function() {
    var table = this.refs.dataTable.getDOMNode().innerHTML;
    var image = this.chart.getImageURI();
    image = '<img src="' + image + '" style="width:600px; height:400px;" />';

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
                <th>Número</th>
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

module.exports = Report5;
