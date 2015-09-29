'use strict';
var React = window.React;
var $http = require('../../http');

function create(e) {
  var topic = this.refs.topic.getDOMNode().value.trim();
  if (!topic) { return; }

  var data = {
    name: topic,
    description: null
  };
  $http.post('/topics', data).then(function(res) {
    if (this.props.onTopicCreated) {
      this.props.onTopicCreated(res);
    }
    this.refs.topic.getDOMNode().value = '';
    this.hideModal();
  }.bind(this));
}

var CreateTopicModal = React.createClass({
  displayName: 'CreateTopicModal',
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
                  <h4 className="modal-title" id="myModalLabel">Adicionar un tema</h4>
                </div>
                <div className="modal-body">
                  <div className="form-group">
                  <label>Tema</label>
                  <input className="form-control" placeholder="Introduzca tema" ref="topic" />
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

module.exports = CreateTopicModal;
