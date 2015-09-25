'use strict';
var React = window.React;
var $http = require('../http');

var months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
function getTitle(type, month, year) {
  if (typeof month !== 'number') {
    month = parseInt(month, 10);
  }
  month = months[month];
  var title = 'Comparación según ';
  if (type === 'press') {
    title += 'medio de comunicación ';
  } else if (type === 'radio') {
    title += 'radiodifusora ';
  } else if (type === 'tv') {
    title += 'canal de televisión ';
  } else {
    title += 'medio ';
  }
  return title + month + ' ' + year;
}

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
      chartRes.push([array[i].name, parseInt(array[i].news, 10)]);
      tableRes.push({name: array[i].name, news: parseInt(array[i].news, 10)});
    }
    var othersTotal = 0;
    for (i = 5 ; i < array.length; ++i) {
      othersTotal += parseInt(array[i].news, 10);
    }
    chartRes.push(['Otros', othersTotal]);
    tableRes.push({name: 'Otros', news: othersTotal});
  } else {
    for (i = 0; i < array.length; ++i) {
      chartRes.push([array[i].name, parseInt(array[i].news, 10)]);
      tableRes.push({name: array[i].name, news: parseInt(array[i].news, 10)});
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
    tpl += '<tr><td>' + data[i].name + '</td><td>' + data[i].news + '</td></tr>';
  }
  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Medio de comunicación');
  data.addColumn('number', 'Noticias');
  data.addRows(reportData);

  var options = {
    title: getTitle(this.props.type, this.props.month, this.props.year),
    width:600,
    height:400,
    is3D: true,
    pieSliceTextStyle: {
      fontSize: 10
    },
    // sliceVisibilityThreshold: 0.05,
    // pieResidueSliceLabel: 'Otros'
  };

  var el = this.refs.chart.getDOMNode();
  var chart = new google.visualization.PieChart(el);
  this.chart = chart;
  chart.draw(data, options);
}

function exportToImage() {
  if (!this.chart) { return };

  var img = this.chart.getImageURI();

  var newWin = window.open('', 'thePopup', 'width=350,height=350');
  newWin.document.write("<html><head><title>popup</title></head><body><h1>Pop</h1>" +
              "<p>Print me</p><a href='print.html' onclick='window.print();return false;'>" +
              "<img src='" + img + "' style='width:100%;height:100%'></a></body></html>");
}

var Report1 = React.createClass({
  getInitialState: function () {
    return {
      data: [],
    };
  },
  componentDidMount: function () {
    if (this.props.data && this.props.data.length > 0) {
      generateReport.call(this);
    }
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
  render: function() {
    return (
      <div>
        <div className="row">
          <div className="col-md-4 col-md-offset-4">
            <table ref="dataTable" className="table table-bordered">
              <thead>
                <th>Medio</th>
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

module.exports = Report1;
