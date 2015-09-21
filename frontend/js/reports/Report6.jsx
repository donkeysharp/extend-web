'use strict';
var React = window.React;


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
      media: key,
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
    tpl += '<tr><td>' + data[i].media + '</td><td>' + data[i].positive + '</td>';
    tpl += '<td>' + data[i].negative + '</td><td>' + data[i].neutral + '</td></tr>';
  }
  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Medio');
  data.addColumn('number', 'Positivo');
  data.addColumn('number', 'Negativo');
  data.addColumn('number', 'Neutro');
  data.addRows(reportData);

  var options = {
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

var Report6 = React.createClass({
  componentDidMount: function () {
    if (this.props.data) {
      generateReport.call(this);
    }
  },
  render: function () {
    return (
      <div>
        <div className="row">
          <div className="col-md-4 col-md-offset-4">
            <table ref="dataTable" className="table table-bordered">
              <thead>
                <th>Medio</th>
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
        <button onClick={exportToImage.bind(this)}>Export</button>
        <img ref="exporter" />
      </div>
    );
  }
});

module.exports = Report6;
