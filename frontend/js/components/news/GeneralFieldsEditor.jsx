'use strict';
var React = window.React;

function onSaveClick(e) {
  alert('foobar');
}

var GeneralFieldsEditor = React.createClass({
  getInitialState: function () {
    return {
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
  render: function(){
    var buttonDisplay = this.state.mode === 'create' ? 'Continuar' : 'Guardar Noticia';

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
            <button className="btn btn-light">
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
        <div className="row">
          <div className="col-md-12">
            <div className="section-divider"><span>IMPRESO</span></div>
            <div className="row">
              <div className="col-md-5">
                <select className="form-control">
                  <option id="123">--- Seleccion Medio ---</option>
                  <option id="123">meg bar </option>
                  <option id="123">meg bar </option>
                </select>
              </div>
              <div className="col-md-1">
                <button className="btn btn-light"><i className="fa fa-plus"></i></button>
              </div>
              <div className="col-md-3">
                <div className="form-group">
                  <div className="input-group">
                    <div className="input-group-addon">
                      <i className="fa fa-envelope"></i>
                    </div>
                    <input type="text" className="form-control" placeholder="Sección" />
                  </div>
                </div>
              </div>
              <div className="col-md-3">
                <div className="form-group">
                  <div className="input-group">
                    <div className="input-group-addon">
                      <i className="fa fa-search"></i>
                    </div>
                    <input type="text" className="form-control" placeholder="Página" />
                  </div>
                </div>
              </div>
            </div>
            <div className="row">
              <div className="col-md-6">
                <div className="form-group">
                  <div className="input-group">
                    <div className="input-group-addon">
                      <i className="fa fa-user"></i>
                    </div>
                    <input type="text" className="form-control" placeholder="Título" />
                  </div>
                </div>
              </div>
              <div className="col-md-6">
                <div className="form-group">
                  <div className="input-group">
                    <div className="input-group-addon">
                      <i className="fa fa-user"></i>
                    </div>
                    <input type="text" className="form-control" placeholder="Género" />
                  </div>
                </div>
              </div>
            </div>
            <div className="row">
              <div className="col-md-5">
                <select className="form-control">
                  <option id="123">--- Seleccione Tema ---</option>
                  <option id="123">meg bar </option>
                  <option id="123">meg bar </option>
                </select>
              </div>
              <div className="col-md-1">
                <button className="btn btn-light" title="Adicionar Tema">
                  <i className="fa fa-plus"></i>
                </button>
              </div>
              <div className="col-md-3">
                <div className="form-group">
                  <div className="input-group">
                    <div className="input-group-addon">
                      <i className="fa fa-sliders"></i>
                    </div>
                    <input type="text" className="form-control" placeholder="Medida" />
                  </div>
                </div>
              </div>
              <div className="col-md-3">
                <div className="form-group">
                  <div className="input-group">
                    <div className="input-group-addon">
                      <i className="fa fa-money"></i>
                    </div>
                    <input type="text" className="form-control" placeholder="Costo" />
                  </div>
                </div>
              </div>
            </div>
            <div className="row">
              <div className="col-md-12">
                <div className="clasification">
                  Tendencia&nbsp;&nbsp;&nbsp;
                  <label>
                    <input type="radio" name="tendency" value="A" checked />
                    Positiva
                  </label>
                  &nbsp;&nbsp;
                  <label>
                    <input type="radio" name="tendency" value="B" />
                    Negativa
                  </label>
                  &nbsp;&nbsp;
                  <label>
                    <input type="radio" name="tendency" value="C" />
                    Neutra
                  </label>
                </div>
              </div>
            </div>
            <br />
            <div className="row">
              <div className="col-md-12">
                <textarea className="form-control" placeholder="Descripción" rows="5"></textarea>
              </div>
            </div>
          </div>
        </div>
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
