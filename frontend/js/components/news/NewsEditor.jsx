'use strict';
var GeneralFieldsEditor = require('./GeneralFieldsEditor.jsx');

var NewsEditor = React.createClass({
  render: function() {
    var newsId = this.props.id;
    var title = !newsId ? 'Nueva Noticia' : 'Edición de Noticia';
    var mode = newsId ? 'edit' : 'create';

    return (
      <div className="row">
        <div className="col-md-8 col-md-offset-2">
          <div className="panel panel-dark">
            <div className="panel-heading"><b>{title}</b></div>
            <div className="panel-body">
              <GeneralFieldsEditor mode={mode} id={newsId} />
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = NewsEditor;
