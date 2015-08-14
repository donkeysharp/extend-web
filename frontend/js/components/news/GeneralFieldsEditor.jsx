'use strict';
var React = window.React;
var PrintedMediaForm = require('./PrintedMediaForm.jsx');
var DigitalMediaForm = require('./DigitalMediaForm.jsx');
var RadioMediaForm = require('./RadioMediaForm.jsx');
var TvMediaForm = require('./TvMediaForm.jsx');
var SourceMediaForm = require('./SourceMediaForm.jsx');

function onSaveClick(e) {
  alert('foobar');
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
      mode: 'create',
      type: {
        'printed': true,
        'digital': true,
        'radio': true,
        'tv': true,
        'source': true,
      }
    };
  },
  render: function() {
    var buttonDisplay = this.state.mode === 'create' ? 'Continuar' : 'Guardar Noticia';
    var mediaForms = getMediaForms.call(this);
    return (
      <div>
        <div className="row">
          <div className="col-md-6">
            <div className="form-group">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-calendar"></i>
                </div>
                <input type="text" className="form-control" placeholder="Fecha" />
              </div>
            </div>
          </div>
          <div className="col-md-5">
            <select className="form-control">
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
                <input type="text" className="form-control" placeholder="Nota de Prensa" />
              </div>
            </div>
          </div>
          <div className="col-md-6">
            <div className="form-group">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-pencil"></i>
                </div>
                <input type="text" className="form-control" placeholder="Subtítulo" />
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
                  <input type="radio" name="clasification" value="A" checked />
                  A
                </label>
                &nbsp;&nbsp;
                <label>
                  <input type="radio" name="clasification" value="B" />
                  B
                </label>
                &nbsp;&nbsp;
                <label>
                  <input type="radio" name="clasification" value="C" />
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
                <input type="text" className="form-control" placeholder="Código" />
              </div>
            </div>
          </div>
        </div>
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
        <div className="row">
          <div className="col-md-12">
            Tipo:&nbsp;&nbsp;&nbsp;
            <label>
              <input type="checkbox" value="printed" />
              &nbsp;Impreso&nbsp;&nbsp;&nbsp;
            </label>
            <label>
              <input type="checkbox" value="digital" />
              &nbsp;Digital&nbsp;&nbsp;&nbsp;
            </label>
            <label>
              <input type="checkbox" value="radio" />
              &nbsp;Radio&nbsp;&nbsp;&nbsp;
            </label>
            <label>
              <input type="checkbox" value="tv" />
              &nbsp;TV&nbsp;&nbsp;&nbsp;
            </label>
            <label>
              <input type="checkbox" value="source" />
              &nbsp;Fuente&nbsp;&nbsp;&nbsp;
            </label>
          </div>
        </div>
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
