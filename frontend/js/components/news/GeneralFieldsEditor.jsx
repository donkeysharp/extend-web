'use strict';
var React = window.React;
var $http = require('../../http');
var MediaType = require('./MediaType.jsx');
var PrintedMediaForm = require('./PrintedMediaForm.jsx');
var DigitalMediaForm = require('./DigitalMediaForm.jsx');
var RadioMediaForm = require('./RadioMediaForm.jsx');
var TvMediaForm = require('./TvMediaForm.jsx');
var SourceMediaForm = require('./SourceMediaForm.jsx');

function onSaveClick(e) {
  var data = {};
    data.date = this.refs.date.getDOMNode().value;
    data.client_id = 1;
    data.press_note = this.refs.pressNote.getDOMNode().value;
    data.subtitle = this.refs.subtitle.getDOMNode().value;
    data.clasification = this.state.clasification;
    data.code = this.refs.code.getDOMNode().value;

  if(this.state.mode === 'create') {
    $http.post('/news', data).then(function(res) {
      console.log(JSON.stringify(res))
    }.bind(this))
  } else {
    // TODO: get data from selected media forms
    // and send them to the server
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

function getMediaForms() {
  var mediaType = this.state.type;
  var result = [];
  if(mediaType.printed) {
    result.push(<PrintedMediaForm />);
  }
  if (mediaType.digital) {
    result.push(<DigitalMediaForm />);
  }
  if (mediaType.radio) {
    result.push(<RadioMediaForm />);
  }
  if (mediaType.tv) {
    result.push(<TvMediaForm />);
  }
  if (mediaType['source']) {
    result.push(<SourceMediaForm />);
  }

  return result;
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
      }
    };
  },
  render: function() {
    var buttonDisplay = this.state.mode === 'create' ? 'Continuar' : 'Guardar Noticia';
    var mediaForms = getMediaForms.call(this);
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
            <select className="form-control" ref="client">
              <option id="123">--- Seleccione Cliente ---</option>
              <option id="123">meg bar </option>
              <option id="123">meg bar </option>
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
        <div className="section-divider"><span>DATOS ADJUNTOS</span></div>
        <div className="row">
          <div className="col-md-6">
            TODO: upload files
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
        <MediaType onChange={onMediaTypeChanged.bind(this)} />
        {mediaForms}
        <br />
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
