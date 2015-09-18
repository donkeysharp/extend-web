'use strict';
var MediaType = require('./MediaType.jsx');
var Dropzone = require('../DropZoneReact.jsx');
var LinkCollapse = require('../LinkCollapse.jsx');
var $http = require('../../http');

function onAddedFile(file, res) {
  var uploads = this.state.uploads;
  uploads.push(res);
  this.setState({uploads: uploads, newsFooter: false, uploadBody: null });

  if (this.props.onAddedFile) {
    this.props.onAddedFile(file);
  }
}

function onMediaTypeChanged(data) {
  if (this.props.onMediaTypeChanged) {
    this.props.onMediaTypeChanged(data);
  }
}

function onNewsFooterChange(e) {
  var data = null;
  if (e.currentTarget.checked) {
    data = { newsFooter: true };
  }
  this.setState({newsFooter: e.currentTarget.checked, uploadBody: data });
}

function onBtnAddURLClicked(e) {
  var url = this.refs.url.getDOMNode().value;
  if (url.trim().length === 0) {
    return;
  }
  this.refs.btnAddUrl.getDOMNode().disabled = true;
  $(this.refs.urlPlusIcon.getDOMNode()).hide();
  $(this.refs.urlSpinIcon.getDOMNode()).show();

  $http.post('/news/' + this.props.newsId +'/urls', {url: url}).then(function(res) {
    this.refs.btnAddUrl.getDOMNode().removeAttribute('disabled');
    $(this.refs.urlPlusIcon.getDOMNode()).show();
    $(this.refs.urlSpinIcon.getDOMNode()).hide();

    this.refs.url.getDOMNode().value = '';
    var urls = this.state.urls;
    urls.push(res);
    this.setState({urls: urls});
    var messages = document.getElementById('messages');
    messages.innerHTML =  '<div class="alert alert-success alert-dismissable">'+
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
      '&times;'+
      '</button>'+
      'URL adicionada exitosamente.'+
      '</div>';
  }.bind(this), function(err) {})
}

function deleteUrl(e) {
  var urlId = e.currentTarget.dataset.urlId;
  var index = e.currentTarget.dataset.index;
  $http.remove('/news/' + this.props.newsId  + '/urls/' + urlId).then(function(res) {
    var urls = this.state.urls;
    urls.splice(index, 1);
    this.setState({urls : urls});
  }.bind(this));
}

function getUrls() {
  if(this.state.urls.length === 0) {
    return <span>No existen URLs</span>;
  }
  var list = this.state.urls.map(function(item, index) {
    return <li>
      <a href={item.url} target="_blank">{item.url}</a>
      &nbsp;-&nbsp;
      <a href="javascript:void(0)" data-index={index} data-url-id={item.id} onClick={deleteUrl.bind(this)}>
        <i className="fa fa-close"></i>
      </a>
    </li>;
  }.bind(this));

  return <ul>{list}</ul>;
}

function deleteUpload(e) {
  var uploadId = e.currentTarget.dataset.uploadId;
  var index = e.currentTarget.dataset.index;
  $http.remove('/news/' + this.props.newsId  + '/uploads/' + uploadId).then(function(res) {
    var uploads = this.state.uploads;
    uploads.splice(index, 1);
    this.setState({uploads : uploads});
  }.bind(this));
}

function getUploads() {
  if (this.state.uploads.length === 0) {
    return <span>No existen Archivos</span>;
  }
  var list = this.state.uploads.map(function(item, index) {
    var prefix = '';
    if (item.news_footer === '1') {
      prefix = '[Pie de nota] - ';
    }
    var fileName = item.file_name;
    if (fileName.length > 20) {
      fileName = fileName.substr(0,20) + '...' + item.type;
    }

    return <li>
      <a href={'/uploads/' + item.file_name} target="_blank">{prefix + fileName}</a>
      &nbsp;-&nbsp;
      <a href="javascript:void(0)" data-upload-id={item.id} data-index={index} onClick={deleteUpload.bind(this)}>
        <i className="fa fa-close"></i>
      </a>
    </li>;
  }.bind(this));

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
      urls: [],
      newsFooter: false,
      uploadBody: null
    };
  },
  componentDidMount: function () {
    $(this.refs.urlSpinIcon.getDOMNode()).hide();
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
              <label>
                <input type="checkbox" checked={this.state.newsFooter} onChange={onNewsFooterChange.bind(this)} />
                &nbsp;
                Pie de noticia
              </label>
              <Dropzone ref="uploader" url={'/news/' + this.props.newsId + '/uploads'}
                acceptedFiles="image/*,application/pdf"
                onAddedFile={onAddedFile.bind(this)}
                maxFilesize={50}
                uploadBody={this.state.uploadBody}
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
                    ref="btnAddUrl"
                    onClick={onBtnAddURLClicked.bind(this)}
                    type="button">
                    <i ref="urlPlusIcon" className="fa fa-plus"></i>
                    <i ref="urlSpinIcon" className="fa fa-spin fa-spinner"></i>
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
