'use strict';
var React = window.React;
var months = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

function parseToDisplay(key) {
  switch(key) {
    case 'press': return 'Prensa';
    case 'radio': return 'Radio';
    case 'tv': return 'Televisión';
  }
}

function generateReport() {
  drawChart.call(this, this.props.data);
  drawTable.call(this, this.props.data);
}

function drawTable(data) {
  var
    table = this.refs.dataTable.getDOMNode(),
    tbody = table.getElementsByTagName('tbody')[0],
    tpl = '';

  var totalPositive = 0, totalNegative = 0, totalNeutral = 0;
  for (var key in data) {
    totalPositive += parseInt(data[key].positive, 10);
    totalNegative += parseInt(data[key].negative, 10);
    totalNeutral += parseInt(data[key].neutral, 10);
  }

  tpl += '<tr>';
  tpl += '<td>Positivo</td><td>' + data.press.positive + '</td>';
  tpl += '<td>' + data.radio.positive + '</td><td>' + data.tv.positive + '</td>';
  tpl += '<td>' + totalPositive + '</td>';
  tpl += '</tr>';
  tpl += '<tr>';
  tpl += '<td>Negativo</td><td>' + data.press.negative + '</td>';
  tpl += '<td>' + data.radio.negative + '</td><td>' + data.tv.negative + '</td>';
  tpl += '<td>' + totalNegative + '</td>';
  tpl += '</tr>';
  tpl += '<tr>';
  tpl += '<td>Neutro</td><td>' + data.press.neutral + '</td>';
  tpl += '<td>' + data.radio.neutral + '</td><td>' + data.tv.neutral + '</td>';
  tpl += '<td>' + totalNeutral + '</td>';
  tpl += '</tr>';

  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Tipo de Medio');
  data.addColumn('number', 'Positivo');
  data.addColumn('number', 'Negativo');
  data.addColumn('number', 'Neutro');
  var pressPositive = parseInt(reportData.press.positive, 10);
  var pressNegative = parseInt(reportData.press.negative, 10);
  var pressNeutral = parseInt(reportData.press.neutral, 10);
  var pressTotal = pressPositive + pressNegative + pressNeutral;

  var radioPositive = parseInt(reportData.radio.positive, 10);
  var radioNegative = parseInt(reportData.radio.negative, 10);
  var radioNeutral = parseInt(reportData.radio.neutral, 10);
  var radioTotal = radioPositive + radioNegative + radioNeutral;

  var tvPositive = parseInt(reportData.tv.positive, 10);
  var tvNegative = parseInt(reportData.tv.negative, 10);
  var tvNeutral = parseInt(reportData.tv.neutral, 10);
  var tvTotal = tvPositive + tvNegative + tvNeutral;

  var totalPositive = pressPositive + radioPositive + tvPositive;
  var totalNegative = pressNegative + radioNegative + tvNegative;
  var totalNeutral = pressNeutral + radioNeutral + tvNeutral;
  var total = totalPositive + totalNegative + totalNeutral;

  data.addRows([
    [
      'Prensa',
      pressPositive > 0 ? (pressPositive * 100.0) / pressTotal : 0,
      pressNegative > 0 ? (pressNegative * 100.0) / pressTotal : 0,
      pressNeutral > 0 ? (pressNeutral * 100.0) / pressTotal : 0,
    ],
    [
      'Radio',
      radioPositive > 0 ? (radioPositive * 100.0) / radioTotal : 0,
      radioNegative > 0 ? (radioNegative * 100.0) / radioTotal : 0,
      radioNeutral > 0 ? (radioNeutral * 100.0) / radioTotal : 0,
    ],
    [
      'Televisión',
      tvPositive > 0 ? (tvPositive * 100.0) / tvTotal : 0,
      tvNegative > 0 ? (tvNegative * 100.0) / tvTotal : 0,
      tvNeutral > 0 ? (tvNeutral * 100.0) / tvTotal : 0,
    ],
    [
      'Total',
      totalPositive > 0 ? (totalPositive * 100.0) / total : 0,
      totalNegative > 0 ? (totalNegative * 100.0) / total : 0,
      totalNeutral  > 0 ? (totalNeutral * 100.0) / total : 0,
    ]
  ]);

  var month = months[parseInt(this.props.month, 10)];
  var title = 'Tendencia por tipo de medio ' + month + ' ' + this.props.year;
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

function exportToImage() {
  if (!this.chart) { return };

  var img = this.chart.getImageURI();

  var newWin = window.open('', 'thePopup', 'width=350,height=350');
  newWin.document.write("<html><head><title>popup</title></head><body><h1>Pop</h1>" +
              "<p>Print me</p><a href='print.html' onclick='window.print();return false;'>" +
              "<img src='" + img + "'></a></body></html>");
}

var GeneralReportB = React.createClass({
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
                <th>Prensa</th>
                <th>Radio</th>
                <th>Televisión</th>
                <th>Total</th>
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

module.exports = GeneralReportB;
