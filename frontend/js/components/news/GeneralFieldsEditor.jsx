'use strict';
var React = window.React;
var $http = require('../../http');
var MediaType = require('./MediaType.jsx');
var PrintedMediaForm = require('./PrintedMediaForm.jsx');
var DigitalMediaForm = require('./DigitalMediaForm.jsx');
var RadioMediaForm = require('./RadioMediaForm.jsx');
var TvMediaForm = require('./TvMediaForm.jsx');
var SourceMediaForm = require('./SourceMediaForm.jsx');

function getMediaFormsData() {
  var mediaType = this.state.type;
  var data = {printed: null, digital: null, radio: null, tv: null, source: null};
  if (mediaType.printed) {
    data.printed = this.refs.printedMedia.getData();
  }
  if (mediaType.digital) {
    data.digital = this.refs.digitalMedia.getData()
  }
  if (mediaType.radio) {
    data.radio = this.refs.radioMedia.getData();
  }
  if (mediaType.tv) {
    data.tv = this.refs.tvMedia.getData();
  }
  if (mediaType['source']) {
    data['source'] = this.refs.sourceMedia.getData();
  }

  return data;
}

function onSaveClick(e) {
  var data = {};
    data.date = this.refs.date.getDOMNode().value;
    data.client_id = this.refs.client.getDOMNode().value;
    data.press_note = this.refs.pressNote.getDOMNode().value;
    data.subtitle = this.refs.subtitle.getDOMNode().value;
    data.clasification = this.state.clasification;
    data.code = this.refs.code.getDOMNode().value;

  if(this.props.mode === 'create') {
    $http.post('/news', data).then(function(res) {
      window.location = '/dashboard/news/' + res.id + '/edit';
    }.bind(this))
  } else {
    data.media = getMediaFormsData.call(this);
    var url = '/news/' + this.props.id;
    $http.put(url, data).then(function(res) {
      // window.location = '/dashboard/news'
    }, function(err) {});
  }
}

function onClasificationChange(e) {
  this.setState({clasification: e.currentTarget.value});
  e.currentTarget.checked = true;
}

function onMediaTypeChanged(data) {
  var mediaType = this.state.type;
  mediaType[data.mediaType] = data.value;
  this.setState({type: mediaType});
}

function parseNewsDetails(data) {
  var res = {printed: null, digital: null, radio: null, tv: null, source: null};
  if(!data.details) return res;

  var details = data.details;
  for(var i = 0; i < details.length; ++i) {
    if (details[i].type === '1') {
      res.printed = details[i];
    } else if (details[i].type === '2') {
      res.digital = details[i];
    } else if (details[i].type === '3') {
      res.radio = details[i];
    } else if (details[i].type === '4') {
      res.tv = details[i];
    } else if (details[i].type === '5') {
      res.source = details[i];
    }
  }
  return res;
}

function getMediaForms() {
  var mediaType = this.state.type;
  var details = parseNewsDetails.call(this, this.state.model);
  var result = [];
  if(mediaType.printed) {
    var model = details.printed;
    result.push(<PrintedMediaForm ref="printedMedia" model={model} media={this.state.media} topics={this.state.topics} />);
  }
  if (mediaType.digital) {
    var model = details.digital;
    result.push(<DigitalMediaForm ref="digitalMedia" model={model} media={this.state.media} topics={this.state.topics} />);
  }
  if (mediaType.radio) {
    var model = details.radio;
    result.push(<RadioMediaForm ref="radioMedia" model={model} media={this.state.media} topics={this.state.topics} />);
  }
  if (mediaType.tv) {
    var model = details.tv;
    result.push(<TvMediaForm ref="tvMedia" model={model} media={this.state.media} topics={this.state.topics} />);
  }
  if (mediaType['source']) {
    var model = details.source;
    result.push(<SourceMediaForm ref="sourceMedia" model={model} media={this.state.media} topics={this.state.topics} />);
  }

  return result;
}

function getExtraFields() {
  var mediaForms = getMediaForms.call(this);
  if(this.props.mode === 'create') return null;

  return (
    <div>
      <div className="section-divider"><span>DATOS ADJUNTOS</span></div>
        <div className="row">
          <div className="col-md-6">
            <div className="dropzone" ref="uploader"></div>
            <br />
            <a href="javascript:void(0)">Ver Archivos</a>
          </div>
          <div className="col-md-6">
            <div className="input-group">
              <input type="text" className="form-control" placeholder="Adicionar URL" />
              <span className="input-group-btn">
                <button className="btn btn-light" type="button">
                  <i className="fa fa-plus"></i>
                </button>
              </span>
            </div>
            <a href="javascript:void(0)">Ver URLS</a>
          </div>
        </div>
        <br />
        <MediaType ref="mediaTypeControl" onChange={onMediaTypeChanged.bind(this)} />
        {mediaForms}
    </div>
  );
}

function initControls(data) {
  this.refs.date.getDOMNode().value = data.date;
  this.refs.pressNote.getDOMNode().value = data.press_note;
  this.refs.subtitle.getDOMNode().value = data.subtitle;
  this.refs.code.getDOMNode().value = data.code;

   var details = parseNewsDetails(data);
   var mediaType = this.state.type;
   mediaType.printed = details.printed ? true : false;
   mediaType.digital = details.digital ? true : false;
   mediaType.radio = details.radio ? true : false;
   mediaType.tv = details.tv ? true : false;
   mediaType.source = details.source ? true : false;

  this.setState({model: data, clasification: data.clasification, type: mediaType});
  this.refs.mediaTypeControl.changeStatus(this.state.type);
}

function getExtraData() {
  var toGet = {clients: true};
  if(this.props.mode === 'edit') {
    toGet.topics = true; toGet.media = true;
  }
  $http.get('/news/extra',toGet).then(function(data) {
    this.setState({clients: data.clients, topics: data.topics, media: data.media });
  }.bind(this), function(err){})
}

function onClientChage(e) {
  if(e.currentTarget.value === '0') return;

  var model = this.state.model;
  model.client_id = e.currentTarget.value;
  this.setState({model: model});
}

var GeneralFieldsEditor = React.createClass({
  getInitialState: function () {
    return {
      id: 0,
      clasification: 'A',
      mode: 'create',
      type: {
        'printed': false,
        'digital': false,
        'radio': false,
        'tv': false,
        'source': false,
      },
      model: {},
      clients: [],
      topics: [],
      media: [],
    };
  },
  componentDidMount: function() {
    getExtraData.call(this);

    if(!this.props.id) return;
    $http.get('/news/' + this.props.id).then(function(data) {
      initControls.call(this, data);
    }.bind(this), function(err) {})

    new Dropzone(this.refs.uploader.getDOMNode(), {url: 'foobar'});
  },
  render: function() {
    var buttonDisplay = this.props.mode === 'create' ? 'Continuar' : 'Guardar Noticia';
    var extraFields = getExtraFields.call(this);
    var clients = this.state.clients.map(function(item) {
      return (<option value={item.id}>{item.name}</option>);
    });
    return (
      <div>
        <div className="section-divider"><span>DATOS GENERALES</span></div>
        <div className="row">
          <div className="col-md-6">
            <div className="form-group">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-calendar"></i>
                </div>
                <input type="text" ref="date" className="form-control" placeholder="Fecha" />
              </div>
            </div>
          </div>
          <div className="col-md-5">
            <select className="form-control"
              value={this.state.model.client_id} ref="client"
              onChange={onClientChage.bind(this)}>
              <option value="0">--- Seleccione Cliente ---</option>
              {clients}
            </select>
          </div>
          <div className="col-md-1">
            <button className="btn btn-light btn-add">
              <i className="fa fa-plus"></i>
            </button>
          </div>
        </div>
        <div className="row">
          <div className="col-md-6">
            <div className="form-group">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-user"></i>
                </div>
                <input type="text" ref="pressNote" className="form-control" placeholder="Nota de Prensa" />
              </div>
            </div>
          </div>
          <div className="col-md-6">
            <div className="form-group">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-pencil"></i>
                </div>
                <input type="text" ref="subtitle" className="form-control" placeholder="Subtítulo" />
              </div>
            </div>
          </div>
        </div>
        <div className="row">
          <div className="col-md-6">
            <div className="form-group">
              <div className="clasification">
                Clasificación&nbsp;&nbsp;&nbsp;
                <label>
                  <input type="radio" name="clasification"
                    onChange={onClasificationChange.bind(this)}
                    checked={this.state.clasification === 'A'}
                    value="A" />
                  A
                </label>
                &nbsp;&nbsp;
                <label>
                  <input type="radio" name="clasification"
                    onChange={onClasificationChange.bind(this)}
                    checked={this.state.clasification === 'B'}
                    value="B" />
                  B
                </label>
                &nbsp;&nbsp;
                <label>
                  <input type="radio" name="clasification"
                    onChange={onClasificationChange.bind(this)}
                    checked={this.state.clasification === 'C'}
                    value="C" />
                  C
                </label>
              </div>
            </div>
          </div>
          <div className="col-md-6">
            <div className="form-group">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-user"></i>
                </div>
                <input type="text" ref="code" className="form-control" placeholder="Código" />
              </div>
            </div>
          </div>
        </div>
        {extraFields}
        <div className="row">
          <div className="col-md-4 col-md-offset-8">
            <button className="btn btn-light btn-block" onClick={onSaveClick.bind(this)}>
              <i className="fa fa-spinner fa-spin"></i>
              <i className="fa fa-save"></i>&nbsp;&nbsp;
              {buttonDisplay}
            </button>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = GeneralFieldsEditor;
