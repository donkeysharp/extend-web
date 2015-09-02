'use strict';
var GeneralFieldsEditor = require('./GeneralFieldsEditor.jsx');

function newsContentLoaded() {
  $(this.refs.spinner.getDOMNode()).hide();
  $(this.refs.editor.getDOMNode()).show();
}

var NewsEditor = React.createClass({
  componentDidMount: function () {
    if (!this.props.id) {
      $(this.refs.spinner.getDOMNode()).hide();
    } else {
      $(this.refs.editor.getDOMNode()).hide();
    }
  },
  render: function() {
    var newsId = this.props.id;
    var title = !newsId ? 'Nueva Noticia' : 'Edición de Noticia';
    var mode = newsId ? 'edit' : 'create';

    return (
      <div className="row">
        <div className="col-md-10 col-md-offset-1">
          <div className="panel panel-dark">
            <div className="panel-heading"><b>{title}</b></div>
            <div className="panel-body">
              <center ref="spinner"><i className="fa fa-spin fa-spinner"></i></center>
              <GeneralFieldsEditor mode={mode} id={newsId}
                ref="editor" newsContentLoaded={newsContentLoaded.bind(this)} />
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = NewsEditor;
