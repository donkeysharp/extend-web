'use strict';
var React = window.React;
var months = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

function getFormattedData(array) {
  array.sort(function(a, b) {
    a = parseInt(a.news, 10);
    b = parseInt(b.news, 10);
    if (a < b) { return 1; }
    if (a > b) { return -1; }
    return 0;
  });
  var i, chartRes = [], tableRes = [];
  for (i = 0; i < array.length; ++i) {
    chartRes.push([array[i].gender, parseInt(array[i].news, 10)]);
    tableRes.push({gender: array[i].gender, news: parseInt(array[i].news, 10)});
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
    tpl += '<tr><td>' + data[i].gender + '</td><td>' + data[i].news + '</td></tr>';
    total += parseInt(data[i].news, 10);
  }
  tpl += '<tr style="background:#eee;font-weight:bolder;"><td>Total</td><td>' + total + '</td></tr>';
  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Género');
  data.addColumn('number', 'Noticias');
  data.addRows(reportData);

  var month = months[parseInt(this.props.month, 10)];
  var title = 'Publicaciones según género ' + month + ' ' + this.props.year;
  var options = {
    title: title,
    width:600,
    height:400,
    hAxis: {
      title: 'Noticias',
      minValue: 0
    },
    vAxis: {
      title: 'Género'
    }
  };

  var el = this.refs.chart.getDOMNode();
  var chart = new google.visualization.BarChart(el);
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

var Report4 = React.createClass({
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
                <th>Género</th>
                <th>Noticias</th>
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

module.exports = Report4;
