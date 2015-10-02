'use strict';
var React = window.React;
var $http = require('../../http');
var ExtraFields = require('./ExtraFields.jsx');
var PrintedMediaForm = require('./PrintedMediaForm.jsx');
var DigitalMediaForm = require('./DigitalMediaForm.jsx');
var RadioMediaForm = require('./RadioMediaForm.jsx');
var TvMediaForm = require('./TvMediaForm.jsx');
var SourceMediaForm = require('./SourceMediaForm.jsx');
var CreateClientModal = require('../modals/CreateClientModal.jsx');

function onDeleteClick(e){
  if(!confirm('Está seguro que desea eliminar esta noticia?')) {return;}

  $http.remove('/news/' + this.props.id).then(function(res) {
    var messages = document.getElementById('messages');
      messages.innerHTML =  '<div class="alert alert-info alert-dismissable">'+
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
      '&times;'+
      '</button>'+
      'Noticia eliminada exitosamente.'+
      '</div>';
      setTimeout(function() {
        window.location = '/dashboard/news/';
      }, 500);
  }, function(err) {})
}

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

function onMediaCreated(res) {
  var media = this.state.media;
  media.push(res);
  this.setState({media: media});
}

function onSourceCreated(res) {
  var sources = this.state.sources;
  sources.push(res);
  this.setState({sources: sources});
}

function onTopicCreated(res) {
  var topics = this.state.topics;
  topics.push(res);
  this.setState({topics: topics});
}

function onSubtitleCreated(res) {
  var subtitles = this.state.subtitles;
  subtitles.push(res);
  this.setState({subtitles: subtitles});
}

function onSaveClick(e) {
  var data = {};
    data.date = this.refs.date.getDOMNode().value;
    data.client_id = this.refs.client.getDOMNode().value;
    data.press_note = this.refs.pressNote.getDOMNode().value;
    data.clasification = this.state.clasification;
    data.code = this.refs.code.getDOMNode().value;

  if(this.props.mode === 'create') {
    this.refs.submitButton.getDOMNode().click();
    $http.post('/news', data).then(function(res) {
      window.location = '/dashboard/news/' + res.id + '/edit';
    }.bind(this))
  } else {
    data.media = getMediaFormsData.call(this);
    var url = '/news/' + this.props.id;
    $http.put(url, data).then(function(res) {
      var messages = document.getElementById('messages');
      messages.innerHTML =  '<div class="alert alert-success alert-dismissable">'+
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
      '&times;'+
      '</button>'+
      'Noticia creada exitosamente.'+
      '</div>';
      setTimeout(function() {
        // window.location = '/dashboard/news/' + res.id + '/edit';
        window.location = '/dashboard/news';
      }, 500);
    }, function(err) {});
  }
}

function getToday() {
  var date = new Date();
  var day, month, year;
  day = date.getDate();
  if (day < 10) {
    day = '0' + day;
  }
  month = date.getMonth() + 1;
  if(month < 10) {
    month = '0' + month;
  }
  year = date.getFullYear();

  return day + '/' + month + '/' + year;
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
    result.push(<PrintedMediaForm ref="printedMedia"
      model={model}
      media={this.state.media}
      topics={this.state.topics}
      subtitles={this.state.subtitles}
      sources={this.state.sources}
      onSubtitleCreated={onSubtitleCreated.bind(this)}
      onSourceCreated={onSourceCreated.bind(this)}
      onTopicCreated={onTopicCreated.bind(this)}
      onMediaCreated={onMediaCreated.bind(this)} />);
  }
  if (mediaType.digital) {
    var model = details.digital;
    result.push(<DigitalMediaForm ref="digitalMedia"
      model={model}
      media={this.state.media}
      topics={this.state.topics}
      subtitles={this.state.subtitles}
      sources={this.state.sources}
      onSubtitleCreated={onSubtitleCreated.bind(this)}
      onSourceCreated={onSourceCreated.bind(this)}
      onTopicCreated={onTopicCreated.bind(this)}
      onMediaCreated={onMediaCreated.bind(this)} />);
  }
  if (mediaType.radio) {
    var model = details.radio;
    result.push(<RadioMediaForm ref="radioMedia"
      model={model}
      media={this.state.media}
      topics={this.state.topics}
      subtitles={this.state.subtitles}
      sources={this.state.sources}
      onSubtitleCreated={onSubtitleCreated.bind(this)}
      onSourceCreated={onSourceCreated.bind(this)}
      onTopicCreated={onTopicCreated.bind(this)}
      onMediaCreated={onMediaCreated.bind(this)} />);
  }
  if (mediaType.tv) {
    var model = details.tv;
    result.push(<TvMediaForm ref="tvMedia"
      model={model}
      media={this.state.media}
      topics={this.state.topics}
      subtitles={this.state.subtitles}
      sources={this.state.sources}
      onSubtitleCreated={onSubtitleCreated.bind(this)}
      onSourceCreated={onSourceCreated.bind(this)}
      onTopicCreated={onTopicCreated.bind(this)}
      onMediaCreated={onMediaCreated.bind(this)} />);
  }
  if (mediaType['source']) {
    var model = details.source;
    result.push(<SourceMediaForm ref="sourceMedia" model={model} media={this.state.media} topics={this.state.topics} subtitles={this.state.subtitles} onSubtitleCreated={onSubtitleCreated.bind(this)} />);
  }

  return result;
}

function getExtraFields() {
  if(this.props.mode === 'create') return null;
  var mediaForms = getMediaForms.call(this);

  return (
    <ExtraFields ref="extraFields" newsId={this.props.id}
      onMediaTypeChanged={onMediaTypeChanged.bind(this)}
      mediaForms={mediaForms}
    />
  );
}

function initControls(data, extraData) {
  this.refs.date.getDOMNode().value = data.date;
  this.refs.pressNote.getDOMNode().value = data.press_note;
  this.refs.code.getDOMNode().value = data.code;

   var details = parseNewsDetails(data);
   var mediaType = this.state.type;
   mediaType.printed = details.printed ? true : false;
   mediaType.digital = details.digital ? true : false;
   mediaType.radio = details.radio ? true : false;
   mediaType.tv = details.tv ? true : false;
   mediaType.source = details.source ? true : false;

  this.setState({
    model: data,
    clasification: data.clasification,
    type: mediaType,
    clients: extraData.clients,
    topics: extraData.topics,
    media: extraData.media,
    subtitles: extraData.subtitles,
    sources: extraData.sources
  });
  this.refs.extraFields.changeMediaTypeStatus(this.state.type);
}

function getExtraData() {
  var toGet = {clients: true};
  if(this.props.mode === 'edit') {
    toGet.topics = true; toGet.media = true; toGet.subtitles = true; toGet.sources = true;
  }
  return $http.get('/news/extra',toGet);
}

function onClientChage(e) {
  if(e.currentTarget.value === '0') return;

  var model = this.state.model;
  model.client_id = e.currentTarget.value;
  this.setState({model: model});
}

function clientCreated(client) {
  var clients = this.state.clients;
  clients.push(client);
  this.setState({clients: clients});
  this.refs.client.getDOMNode().value = client.id;
}

function displayClientModal(e) {
  this.refs.clientModal.showModal()
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
      sources: [],
    };
  },
  componentDidMount: function() {
    $(this.refs.date.getDOMNode()).datepicker({
      format: 'dd/mm/yyyy',
      language: 'es',
      orientation: "top right",
      todayHighlight: true,
      autoclose: true
    }).on('hide', function(e) {
      if (e.dates.length === 0) {
        this.refs.date.getDOMNode().value = getToday();
      }
    }.bind(this));
    this.refs.date.getDOMNode().value = getToday();
    getExtraData.call(this).then(function(extraData) {
      if(!this.props.id) {
        this.setState({clients: extraData.clients});
        return
      };
      $http.get('/news/' + this.props.id).then(function(data) {
        initControls.call(this, data, extraData);
        if (this.props.newsContentLoaded) {
          this.props.newsContentLoaded();
        }
      }.bind(this), function(err) {})
    }.bind(this), function(err){});
  },
  render: function() {
    var buttonDisplay = this.props.mode === 'create' ? 'Continuar' : 'Guardar Noticia';
    var extraFields = getExtraFields.call(this);
    var clients = this.state.clients.map(function(item) {
      return (<option value={item.id}>{item.name}</option>);
    });
    var deleteButton = '';
    if(this.props.mode === 'edit') {
      deleteButton = <button className="btn btn-danger" onClick={onDeleteClick.bind(this)}>
              <i className="fa fa-trash"></i>&nbsp;&nbsp;
              Eliminar Noticia
            </button>;
    }
    return (
      <div>
        <CreateClientModal ref="clientModal" onItemCreated={clientCreated.bind(this)} />
        <div className="section-divider"><span>DATOS GENERALES</span></div>
        <iframe src="/blank" className="hidden" name="news_iframe" id="news_iframe"></iframe>
        <form target="news_iframe" action="/blank" method="POST" >
        <div className="row">
          <div className="col-md-6">
            <div className="form-group">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-calendar"></i>
                </div>
                <input type="text" ref="date" name="date" className="form-control" placeholder="Fecha" />
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
            <a className="btn btn-light btn-add" href="javascript:void(0)" onClick={displayClientModal.bind(this)}>
              <i className="fa fa-plus"></i>
            </a>
          </div>
        </div>
        <div className="row">
          <div className="col-md-6">
            <div className="form-group">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-user"></i>
                </div>
                <input type="text" ref="pressNote" name="pressNote" className="form-control" placeholder="Nota de Prensa" />
              </div>
            </div>
          </div>
          <div className="col-md-6">
            <div className="form-group">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-user"></i>
                </div>
                <input type="text" ref="code" name="code" className="form-control" placeholder="Código" />
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
        </div>
        {extraFields}
        <br />
        <div className="row">
          <div className="col-md-12">
            <button type="submit" ref="submitButton" className="hidden">Submit</button>
            <button className="btn btn-light" type="button" onClick={onSaveClick.bind(this)}>
              <i className="fa fa-save"></i>&nbsp;&nbsp;
              {buttonDisplay}
            </button>
            &nbsp;
            {deleteButton}
            &nbsp;
            <a href="/dashboard/news">Volver</a>
          </div>
        </div>
        </form>
      </div>
    );
  }
});

module.exports = GeneralFieldsEditor;
