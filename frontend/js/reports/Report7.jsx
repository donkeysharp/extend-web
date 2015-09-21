'use strict';
var React = window.React;


var Report7 = React.createClass({
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

module.exports = Report7;
