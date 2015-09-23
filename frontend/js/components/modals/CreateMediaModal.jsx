'use strict';
var React = window.React;
var $http = require('../../http');

function create(e) {
  var
    name = this.refs.name.getDOMNode().value.trim(),
    type = this.refs.type.getDOMNode().value.trim(),
    city = this.refs.city.getDOMNode().value.trim(),
    website = this.refs.website.getDOMNode().value.trim();

  if (!name || !city || !type) { return; }
  var data = {
    name: name,
    type: type,
    city: city,
    website: website,
    description: null
  };
  $http.post('/media', data).then(function(res) {
    if (this.props.onItemCreated) {
      this.props.onItemCreated(res);
    }
    this.refs.name.getDOMNode().value = '';
    this.refs.type.getDOMNode().value = '';
    this.refs.city.getDOMNode().value = '';
    this.refs.website.getDOMNode().value = '';
    this.hideModal();
  }.bind(this));
}

var CreateClientModal = React.createClass({
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
                  <h4 className="modal-title">Creación de medio</h4>
                </div>
                <div className="modal-body">
                  <div className="row">
                    <div className="col-md-7">
                      <div className="form-group">
                        <div className="input-group">
                          <div className="input-group-addon">
                            <i className="fa fa-user"></i>
                          </div>
                          <input type="text" ref="name" className="form-control" placeholder="Nombre" />
                        </div>
                      </div>
                    </div>
                    <div className="col-md-5">
                      <div className="form-group">
                        <select className="form-control" ref="type">
                          <option value="" selected="selected">--- Seleccione un tipo ---</option>
                          <option value="1">Impreso</option>
                          <option value="2">Digital</option>
                          <option value="3">Radio</option>
                          <option value="4">TV</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div className="row">
                    <div className="col-md-7">
                      <div className="form-group">
                        <select ref="city" className="form-control">
                          <option value="">--- Seleccione una ciudad ---</option>
                          <option value="Beni">Beni</option>
                          <option value="Cochabamba">Cochabamba</option>
                          <option value="La Paz">La Paz</option>
                          <option value="Oruro">Oruro</option>
                          <option value="Pando">Pando</option>
                          <option value="Potosi">Potos&iacute;</option>
                          <option value="Santa Cruz">Santa Cruz</option>
                          <option value="Sucre">Sucre</option>
                          <option value="Tarija">Tarija</option>
                        </select>
                      </div>
                    </div>
                    <div className="col-md-5">
                      <div className="form-group">
                        <div className="input-group">
                          <div className="input-group-addon">
                            <i className="fa fa-user"></i>
                          </div>
                          <input type="text" ref="website" className="form-control" placeholder="Sitio Web" />
                        </div>
                      </div>
                    </div>
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

module.exports = CreateClientModal;
