'use strict';

function onTendencyChange(e) {
  this.setState({tendency: e.currentTarget.value});
  e.currentTarget.checked = true;
}

var SourceMediaForm = React.createClass({
  getInitialState: function () {
    return {
      tendency: '1'
    };
  },
  getData: function() {
    var data = {};
    data.media_id = this.refs.media.getDOMNode().value;
    data.source = this.refs.source.getDOMNode().value;
    data.alias = this.refs.alias.getDOMNode().value;
    data.topic_id = this.refs.topic.getDOMNode().value;
    data.measure = this.refs.measure.getDOMNode().value;
    data.cost = this.refs.cost.getDOMNode().value;
    data.tendency = this.state.tendency;
    data.description = this.refs.description.getDOMNode().value;

    return data;
  },
  render: function() {
    var media = this.props.media.map(function(item) {
      if(item.type === '5')
        return <option value={item.id}>{item.name}</option>
    });
    var topics = this.props.topics.map(function(item) {
      return <option value={item.id}>{item.name}</option>;
    });
    return (
      <div className="row">
        <div className="col-md-12">
          <div className="section-divider"><span>FUENTE</span></div>
          <div className="row">
            <div className="col-md-10">
              <select ref="media" className="form-control">
                <option value="0">--- Seleccione Medio ---</option>
                {media}
              </select>
            </div>
            <div className="col-md-1">
              <button className="btn btn-light btn-add" title="Adicionar Tema">
                <i className="fa fa-plus"></i>
              </button>
            </div>
          </div><br />
          <div className="row">
            <div className="col-md-5">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-user"></i>
                </div>
                <input type="text" ref="source" className="form-control" placeholder="Fuente" />
              </div>
            </div>
            <div className="col-md-7">
              <div className="input-group">
                <div className="input-group-addon">
                  <i className="fa fa-user"></i>
                </div>
                <input type="text" ref="alias" className="form-control" placeholder="Alias" />
              </div>
            </div>
          </div><br />
          <div className="row">
            <div className="col-md-5">
              <select ref="topic" className="form-control">
                <option value="0">--- Seleccione Tema ---</option>
                {topics}
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
                  <input type="text" ref="measure" className="form-control" placeholder="Medida" />
                </div>
              </div>
            </div>
            <div className="col-md-3">
              <div className="form-group">
                <div className="input-group">
                  <div className="input-group-addon">
                    <i className="fa fa-money"></i>
                  </div>
                  <input type="text" ref="cost" className="form-control" placeholder="Costo" />
                </div>
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-12">
              <div className="clasification">
                Tendencia&nbsp;&nbsp;&nbsp;
                <label>
                  <input type="radio" name="tendency_source" value="1"
                    onChange={onTendencyChange.bind(this)}
                    checked={this.state.tendency === '1'} />
                  Positiva
                </label>
                &nbsp;&nbsp;
                <label>
                  <input type="radio" name="tendency_source" value="2"
                    onChange={onTendencyChange.bind(this)}
                    checked={this.state.tendency === '2'} />
                  Negativa
                </label>
                &nbsp;&nbsp;
                <label>
                  <input type="radio" name="tendency_source" value="3"
                    onChange={onTendencyChange.bind(this)}
                    checked={this.state.tendency === '3'} />
                  Neutra
                </label>
              </div>
            </div>
          </div>
          <br />
          <div className="row">
            <div className="col-md-12">
              <textarea ref="description" className="form-control" placeholder="Descripción" rows="5"></textarea>
            </div>
          </div>
        </div>
      </div>
    );
  }
});


module.exports = SourceMediaForm;
