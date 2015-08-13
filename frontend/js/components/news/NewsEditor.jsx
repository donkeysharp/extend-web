'use strict';
var GeneralFieldsEditor = require('./GeneralFieldsEditor.jsx');

var NewsEditor = React.createClass({
  getInitialState: function () {
    return {
      mode: 'create', // create, edit
    };
  },
  render: function() {
    var title = this.state.mode === 'create' ? 'Nueva Noticia' : 'Edición de Noticia';

    return (
      <div className="row">
        <div className="col-md-8 col-md-offset-2">
          <div className="panel panel-dark">
            <div className="panel-heading"><b>{title}</b></div>
            <div className="panel-body">
              <GeneralFieldsEditor />
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = NewsEditor;
