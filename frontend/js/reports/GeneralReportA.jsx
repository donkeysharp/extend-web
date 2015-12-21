'use strict';
var React = window.React;
var labelify = require('../helpers').labelify;
var parseLabel = require('../helpers').parseLabel;
var months = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];


function generateReport() {
  drawChart.call(this, this.props.data);
  drawTable.call(this, this.props.data);
}

function drawTable(data) {
  var table = this.refs.dataTable.getDOMNode(),
    tbody = table.getElementsByTagName('tbody')[0];
  var tpl = '';
  tpl += '<tr><td>Prensa</td><td>' + data.press + '</td></tr>';
  tpl += '<tr><td>Radio</td><td>' + data.radio + '</td></tr>';
  tpl += '<tr><td>Televisión</td><td>' + data.tv + '</td></tr>';
  tpl += '<tr style="background: #eee;font-weight:bolder;"><td>Total</td><td>' + (data.press + data.radio + data.tv) + '</td></tr>';
  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data = new google.visualization.DataTable();
  var total = reportData.press + reportData.radio + reportData.tv;
  data.addColumn('string', 'Tipo de Medio');
  data.addColumn('number', 'Noticias');
  data.addRows([
    [parseLabel((reportData.press * 100.0) / total, 'Prensa', 1), reportData.press],
    [parseLabel((reportData.radio * 100.0) / total, 'Radio', 1), reportData.radio],
    [parseLabel((reportData.tv * 100.0) / total, 'Televisión', 1), reportData.tv]
  ]);

  var month = months[parseInt(this.props.month, 10)];
  var title = 'Difusión por tipo de medio ' + month + ' ' + this.props.year;
  var options = {
    title: title,
    width:600,
    height:400,
    is3D: true,
    pieSliceText: 'percentage',
    legend: {
      // position: 'labeled'
    },
    pieSliceTextStyle: {
      fontSize: 10
    },
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
              "<img src='" + img + "'></a></body></html>");
}

var GeneralReportA = React.createClass({
  componentDidMount: function () {
    // if (this.props.data) {
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
                <th>Tipo de Medio</th>
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

module.exports = GeneralReportA;
