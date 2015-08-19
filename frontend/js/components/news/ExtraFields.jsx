'use strict';
var MediaType = require('./MediaType.jsx');
var Dropzone = require('../DropZoneReact.jsx');
var LinkCollapse = require('../LinkCollapse.jsx');
var $http = require('../../http');

function onAddedFile(file) {
  if (this.props.onAddedFile) {
    this.props.onAddedFile(file);
  }
}

function onMediaTypeChanged(data) {
  if (this.props.onMediaTypeChanged) {
    this.props.onMediaTypeChanged(data);
  }
}

function onBtnAddURLClicked(e) {
  var url = this.refs.url.getDOMNode().value;
  $http.post('/news/' + this.props.newsId +'/urls', {url: url}).then(function(res) {
    console.log('url added');
    this.refs.url.getDOMNode().value = '';
    var messages = document.getElementById('messages');
    messages.innerHTML =  '<div class="alert alert-success alert-dismissable">'+
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
      '&times;'+
      '</button>'+
      'URL adicionada exitosamente.'+
      '</div>';
  }.bind(this), function(err) {})
}

function getUrls() {
  var list = this.state.urls.map(function(item) {
    return <li><a href={item.url} target="_blank">{item.url}</a></li>;
  });

  return <ul>{list}</ul>;
}

function getUploads() {
  var list = this.state.uploads.map(function(item) {
    return <li><a href={'/uploads/' + item.file_name} target="_blank">{item.file_name}</a></li>;
  });

  return <ul>{list}</ul>;
}

var ExtraFields = React.createClass({
  displayName: 'ExtraFields',
  changeMediaTypeStatus: function(types) {
    this.refs.mediaTypeControl.changeStatus(types);
  },
  getInitialState: function () {
    return {
      uploads: [],
      urls: []
    };
  },
  componentDidMount: function () {
    $http.get('/news/'+this.props.newsId+'/urls').then(function(res) {
      this.setState({urls: res});
    }.bind(this), function(err){})
    $http.get('/news/'+this.props.newsId+'/uploads').then(function(res) {
      this.setState({uploads: res});
    }.bind(this), function(err) {});
  },
  render: function () {
    var uploads = getUploads.call(this);
    var urls = getUrls.call(this);
    return (
      <div>
        <div className="section-divider"><span>DATOS ADJUNTOS</span></div>
          <div className="row">
            <div className="col-md-6">
              <Dropzone ref="uploader" url={'/upload/' + this.props.newsId}
                acceptedFiles="image/*,application/pdf"
                onAddedFile={onAddedFile.bind(this)}
                maxFilesize={50}
              />
              <LinkCollapse linkText="Ver Archivos" content={uploads} />

            </div>
            <div className="col-md-6">
              <div className="input-group">
                <input type="text" ref="url"
                  className="form-control"
                  placeholder="Adicionar URL" />
                <span className="input-group-btn">
                  <button className="btn btn-light"
                    onClick={onBtnAddURLClicked.bind(this)}
                    type="button">
                    <i className="fa fa-plus"></i>
                  </button>
                </span>
              </div>
              <LinkCollapse linkText="Ver URLs" content={urls} />
            </div>
          </div>
          <br />
          <MediaType ref="mediaTypeControl" onChange={onMediaTypeChanged.bind(this)} />
          {this.props.mediaForms}
      </div>
    );
  }
});

module.exports = ExtraFields;
