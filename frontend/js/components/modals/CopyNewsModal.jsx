'use strict';
var React = window.React;
var $http = require('../../http');

function copyNews(e) {
  var clientId = this.refs.clients.getDOMNode().value;
  var newsId = this.refs.newsId.getDOMNode().value;
  if (!clientId || !newsId) { return; }

  $http.post('/news/' + newsId + '/copy/' + clientId).then(function(res) {
    if (this.props.onNewsCopied) {
      this.props.onNewsCopied(res);
    }
  }.bind(this));
}

var CopyNewsModal = React.createClass({
  getInitialState: function () {
    return {
      id: 'modal_' + Math.random()
    };
  },
  hideModal: function() {
    $(this.refs.myModal.getDOMNode()).modal('hide');
  },
  showModal: function(newsId) {
    $(this.refs.myModal.getDOMNode()).modal('show');
    this.refs.newsId.getDOMNode().value = newsId;
  },
  render: function() {
    var clients = this.props.clients.map(function(item) {
      return <option value={item.id}>{item.name}</option>;
    });
    return (
      <div className="row">
        <div className="col-md-4">
          <div className="modal fade" ref="myModal" id={this.state.id} tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div className="modal-dialog" role="document">
              <div className="modal-content">
                <div className="modal-header">
                  <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 className="modal-title" id="myModalLabel">Copiar noticia</h4>
                </div>
                <div className="modal-body">
                  <div className="row">
                    <div className="col-md-12">
                      <div className="form-group">
                        <select className="form-control" ref="clients">
                          {clients}
                        </select>
                        <input type="hidden" ref="newsId" value="" />
                      </div>
                    </div>
                  </div>
                </div>
                <div className="modal-footer">
                  <a href="javascript:void(0)" data-dismiss="modal">Cancelar</a>
                  &nbsp;&nbsp;
                  <button onClick={copyNews.bind(this)} type="button" className="btn btn-light">
                    <i className="fa fa-copy"></i>&nbsp;
                    Copiar noticia
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

module.exports = CopyNewsModal;
