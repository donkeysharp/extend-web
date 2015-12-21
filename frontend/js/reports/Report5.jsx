'use strict';
var React = window.React;
var labelify = require('../helpers').labelify;
var parseLabel = require('../helpers').parseLabel;
var months = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

function getFormattedData(array) {
  array.sort(function(a, b) {
    a = parseInt(a.news, 10);
    b = parseInt(b.news, 10);
    if (a < b) { return 1; }
    if (a > b) { return -1; }
    return 0;
  });

  var chartRes = [], tableRes = [], i;
  var total = 0;
  if (array.length > 5) {
    for (i = 0; i < 5; ++i) {
      tableRes.push({source: array[i].source, news: parseInt(array[i].news, 10)});
      total += parseInt(array[i].news, 10);
    }
    var othersTotal = 0;
    for (i = 5 ; i < array.length; ++i) {
      othersTotal += parseInt(array[i].news, 10);
    }
    total += othersTotal;
    tableRes.push({source: 'Otros', news: othersTotal});
  } else {
    for (i = 0; i < array.length; ++i) {
      tableRes.push({source: array[i].source, news: parseInt(array[i].news, 10)});
      total += parseInt(array[i].news, 10);
    }
  }

  if (array.length > 5) {
    for (i = 0; i < 5; ++i) {
      var value = parseInt(array[i].news, 10);
      var label = parseLabel((value * 100.0) / total, array[i].source, 1);
      chartRes.push([label, value]);
    }
    var othersTotal = 0;
    for (i = 5 ; i < array.length; ++i) {
      othersTotal += parseInt(array[i].news, 10);
    }
    var label = parseLabel((othersTotal * 100.0) / total, 'Otros', 1);
    chartRes.push([label, othersTotal]);
  } else {
    for (i = 0; i < array.length; ++i) {
      var value = parseInt(array[i].news, 10);
      var label = parseLabel((value * 100.0) / total, array[i].source, 1);
      chartRes.push([label, value]);
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
  var total = 0;
  for (var i = 0; i < data.length; ++i) {
    tpl += '<tr><td>' + data[i].source + '</td><td>' + data[i].news + '</td></tr>';
    total += parseInt(data[i].news, 10);
  }
  tpl += '<tr style="background:#eee;font-weight:bolder;"><td>Total</td><td>' + total + '</td></tr>';
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
    pieSliceText: 'percentage',
    legend: {
      // position: 'labeled',
      textStyle: {
        // fontName: 'monospace',
        fontSize: 9
      }
    },
    pieSliceTextStyle: {
      fontSize: 8
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
    var table = this.refs.dataTable.getDOMNode();
    var tHead = table.tHead, tBody = table.tBodies[0];
    var data = [], tmp = [];

    for (var j = 0; j < tHead.children[0].children.length; ++j) {
      tmp.push(tHead.children[0].children[j].innerHTML);
    }
    data.push(tmp);

    for (var i = 0; i < tBody.children.length; ++i) {
      tmp = [];
      for (var j = 0; j < tBody.children[i].children.length; ++j) {
        tmp.push(tBody.children[i].children[j].innerHTML);
      }
      data.push(tmp);
    }
    var image = this.chart.getImageURI();

    return {
      table: data,
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
