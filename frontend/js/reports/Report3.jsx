'use strict';
var React = window.React;
var $http = require('../http');


function generateReport() {
  drawChart.call(this, this.props.data);
  drawTable.call(this, this.props.data);
}

function drawTable(data) {
  var table = this.refs.dataTable.getDOMNode(),
    tbody = table.getElementsByTagName('tbody')[0],
    tpl = '';

  tpl += '<tr><td>' + data.client + '</td><td>' + data.positive + '</td>';
  tpl += '<td>' + data.negative + '</td><td>' + data.neutral + '</td></tr>';

  tbody.innerHTML = tpl;
}

function drawChart(reportData) {
  var data1 = google.visualization.arrayToDataTable([
    ['Tendencia', 'Tendencia', {role: 'style'} ],
    ['Positivo', parseInt(reportData.positive, 10), '#4285F4'],
    ['Negativo', parseInt(reportData.negative, 10), '#DB4437' ],
    ['Neutro', parseInt(reportData.neutral, 10), '#5F7E8A' ]
  ]);

  var data2 = new google.visualization.DataTable();
  data2.addColumn('string', 'Tendencia');
  data2.addColumn('number', 'Noticias');
  data2.addRows([
    [ 'Positivo', parseInt(reportData.positive, 10) ],
    [ 'Negativo', parseInt(reportData.negative, 10) ],
    [ 'Neutro', parseInt(reportData.neutral, 10) ],
  ]);

  var options1 = {
    width: 600,
    height: 400,
    legend: { position: 'top', maxLines: 3 },
    bar: { groupWidth: '75%' },
    vAxis: {
      minValue: 0,
    }
  };

  var options2 = {
    width:600,
    height:400,
    is3D: true,
    pieSliceTextStyle: {
      fontSize: 10
    },
    // sliceVisibilityThreshold: 0.05,
    // pieResidueSliceLabel: 'Otros'
  };

  var chart1 = new google.visualization.ColumnChart(this.refs.chart1.getDOMNode());
  this.chart1 = chart1;
  chart1.draw(data1, options1);

  var chart2 = new google.visualization.PieChart(this.refs.chart2.getDOMNode());
  this.chart2 = chart2;
  chart2.draw(data2, options2);
}

function exportToImage() {
  if (!this.chart1) { return };
  if (!this.chart2) { return };

  var img1 = this.chart1.getImageURI();
  var img2 = this.chart2.getImageURI();

  var newWin = window.open('', 'thePopup', 'width=350,height=350');
  newWin.document.write("<html><head><title>popup</title></head><body><h1>Pop</h1>" +
              "<p>Print me</p><a href='print.html' onclick='window.print();return false;'>" +
              "<img src='" + img1 + "'><br><img src='"+img2+"'></a></body></html>");
}


var Report3 = React.createClass({
  componentDidMount: function () {
    if (this.props.data) {
      generateReport.call(this);
    }
  },
  getExportData: function() {
    var table = this.refs.dataTable.getDOMNode().innerHTML;
    var image = this.chart1.getImageURI();
    image = '<img src="' + image + '" />';

    var image2 = this.chart2.getImageURI();
    image2 = '<img src="' + image2 + '" />';

    return {
      table: table,
      image: image,
      image2: image2
    };
  },
  render: function () {
    return (
      <div>
        <div className="row">
          <div className="col-md-4 col-md-offset-4">
            <table ref="dataTable" className="table table-bordered">
              <thead>
                <th>Cliente</th>
                <th>Positivo</th>
                <th>Negativo</th>
                <th>Neutros</th>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <center>
          <div ref="chart1"></div>
          <div ref="chart2"></div>
        </center>
      </div>
    );
  }
});

module.exports = Report3;
