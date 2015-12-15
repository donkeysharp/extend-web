'use strict';
var React = window.React;
var months = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

function getTitle(type, month, year) {
  var title = 'Tendencia '
  if (type === 'press') {
    title += 'porcentual ';
  }
  title += 'por medio ' + month + ' ' + year;

  return title;
}

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
      positive: parseInt(data[key].positive, 10),
      negative: parseInt(data[key].negative, 10),
      neutral: parseInt(data[key].neutral, 10)
    });
  }
  // Descending sort
  chartRes.sort(function(a, b) {
    var totalA = a[1] + a[2] + a[3];
    var totalB = b[1] + b[2] + b[3];
    if (totalA < totalB) {
      return 1;
    } else if (totalA > totalB) {
      return -1;
    }
    return 0;
  });
  tableRes.sort(function(a, b) {
    var totalA = a.positive + a.negative + a.neutral;
    var totalB = b.positive + b.negative + b.neutral;
    if (totalA < totalB) {
      return 1;
    } else if (totalA > totalB) {
      return -1;
    }
    return 0;
  });
  for (var i = 0; i < chartRes.length; ++i) {
    var total = chartRes[i][1] + chartRes[i][2] + chartRes[i][3];
    chartRes[i][1] = chartRes[i][1] > 0 ? (chartRes[i][1] * 100) / total : 0;
    chartRes[i][2] = chartRes[i][2] > 0 ? (chartRes[i][2] * 100) / total : 0;
    chartRes[i][3] = chartRes[i][3] > 0 ? (chartRes[i][3] * 100) / total : 0;
  }

  if (chartRes.length > 5 && tableRes.length > 5) {
    chartRes.splice(5);
    tableRes.splice(5);
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

  var totalPositive = 0, totalNegative = 0, totalNeutral = 0;
  for (var i = 0; i < data.length; ++i) {
    tpl += '<tr><td>' + data[i].media + '</td><td>' + data[i].positive + '</td>';
    tpl += '<td>' + data[i].negative + '</td><td>' + data[i].neutral + '</td></tr>';
    totalPositive += parseInt(data[i].positive, 10);
    totalNegative += parseInt(data[i].negative, 10);
    totalNeutral += parseInt(data[i].neutral, 10);
  }
  tpl += '<tr style="background:#eee;font-weight:bolder;"><td>Total</td>';
  tpl += '<td>' + totalPositive + '</td>';
  tpl += '<td>' + totalNegative + '</td>';
  tpl += '<td>' + totalNeutral + '</td></tr>';
  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Medio');
  data.addColumn('number', 'Positivo');
  data.addColumn('number', 'Negativo');
  data.addColumn('number', 'Neutro');
  data.addRows(reportData);

  var month = months[parseInt(this.props.month, 10)];
  // var title = 'Tendencia porcentual por medio ' + month + ' ' + this.props.year;
  var title = getTitle(this.props.type, month, this.props.year);
  var options = {
    title: title,
    width:600,
    height:400,
    legend: {
      position: 'top',
      maxLines: 3,
    },
    hAxis: {
      textStyle: {
        fontSize: 10
      },
      slantedText: false,
      showTextEvery: 1
    },
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
      </div>
    );
  }
});

module.exports = Report6;
