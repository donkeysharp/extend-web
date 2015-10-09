'use strict';
var React = window.React;
var months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

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

  for (var key in data) {
    tpl += '<tr><td>' + parseToDisplay(key) + '</td><td>' + data[key].positive + '</td>';
    tpl += '<td>' + data[key].negative + '</td><td>' + data[key].neutral + '</td></tr>';
  }
  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Tipo de Medio');
  data.addColumn('number', 'Positivo');
  data.addColumn('number', 'Negativo');
  data.addColumn('number', 'Neutro');
  data.addRows([
    ['Prensa', parseInt(reportData.press.positive, 10), parseInt(reportData.press.negative, 10), parseInt(reportData.press.neutral, 10)],
    ['Radio', parseInt(reportData.radio.positive, 10), parseInt(reportData.radio.negative, 10), parseInt(reportData.radio.neutral, 10)],
    ['Televisión', parseInt(reportData.tv.positive, 10), parseInt(reportData.tv.negative, 10), parseInt(reportData.tv.neutral, 10)],
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
                <th>Tipo de Medio</th>
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

module.exports = GeneralReportB;
