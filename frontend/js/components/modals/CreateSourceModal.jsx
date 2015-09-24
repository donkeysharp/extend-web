'use strict';
var React = window.React;
var $http = require('../../http');

function create(e) {
  var source = this.refs.source.getDOMNode().value.trim();
  if (!source) { return; }

  var data = {
    source: source
  };
  $http.post('/sources', data).then(function(res) {
    if (this.props.onSourceCreated) {
      this.props.onSourceCreated(res);
    }
    this.refs.source.getDOMNode().value = '';
    this.hideModal();
  }.bind(this));
}

var CreateSourceModal = React.createClass({
  displayName: 'CreateSourceModal',
  getInitialState: function () {
    return {
      id: 'modal_' + Math.random()
    };
  },
  hideModal: function() {
    $(this.refs.myModal.getDOMNode()).modal('hide');
  },
  showModal: function() {
    $(this.refs.myModal.getDOMNode()).modal('show');
  },
  render: function () {
    return (
      <div className="row">
        <div className="col-md-4">
          <div className="modal fade" ref="myModal" id={this.state.id} tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div className="modal-dialog" role="document">
              <div className="modal-content">
                <div className="modal-header">
                  <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 className="modal-title" id="myModalLabel">Adicionar una fuente</h4>
                </div>
                <div className="modal-body">
                  <div className="form-group">
                  <label>Fuente</label>
                  <input className="form-control" placeholder="Introduzca fuente" ref="source" />
                  </div>
                </div>
                <div className="modal-footer">
                  <a href="javascript:void(0)" data-dismiss="modal">Cancelar</a>
                  &nbsp;&nbsp;
                  <button onClick={create.bind(this)} type="button" className="btn btn-light">
                    <i className="fa fa-save"></i>&nbsp;
                    Guardar Cambios
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = CreateSourceModal;
