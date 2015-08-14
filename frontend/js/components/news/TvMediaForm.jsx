'use strict';

var TvMediaForm = React.createClass({
  render: function() {
    return (
      <div className="row">
        <div className="col-md-12">
          <div className="section-divider"><span>TV</span></div>
          <div className="row">
            <div className="col-md-5">
              <select className="form-control">
                <option id="123">--- Seleccion Medio ---</option>
                <option id="123">meg bar </option>
                <option id="123">meg bar </option>
              </select>
            </div>
            <div className="col-md-1">
              <button className="btn btn-light btn-add"><i className="fa fa-plus"></i></button>
            </div>
            <div className="col-md-3">
              <div className="form-group">
                <div className="input-group">
                  <div className="input-group-addon">
                    <i className="fa fa-envelope"></i>
                  </div>
                  <input type="text" className="form-control" placeholder="Fuente" />
                </div>
              </div>
            </div>
            <div className="col-md-3">
              <div className="form-group">
                <div className="input-group">
                  <div className="input-group-addon">
                    <i className="fa fa-search"></i>
                  </div>
                  <input type="text" className="form-control" placeholder="Alias" />
                </div>
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-12">
              <div className="form-group">
                <div className="input-group">
                  <div className="input-group-addon">
                    <i className="fa fa-user"></i>
                  </div>
                  <input type="text" className="form-control" placeholder="Título" />
                </div>
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-7">
              <div className="form-group">
                <div className="input-group">
                  <div className="input-group-addon">
                    <i className="fa fa-user"></i>
                  </div>
                  <input type="text" className="form-control" placeholder="Risgo Comunicacional" />
                </div>
              </div>
            </div>
            <div className="col-md-5">
              <div className="form-group">
                <div className="input-group">
                  <div className="input-group-addon">
                    <i className="fa fa-user"></i>
                  </div>
                  <input type="text" className="form-control" placeholder="Programa" />
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
              <button className="btn btn-light btn-add" title="Adicionar Tema">
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
    );
  }
});


module.exports = TvMediaForm;
