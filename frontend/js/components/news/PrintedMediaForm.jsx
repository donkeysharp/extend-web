'use strict';
var ModalSubtitleCreateForm = require('../subtitles/ModalSubtitleCreateForm.jsx');

function displayModal() {
  this.refs.subtitleModal.showModal();
}

function onSubtitleCreated(res) {
  this.refs.subtitle.getDOMNode().value = res.subtitle;
  if(this.props.onSubtitleCreated) {
    this.props.onSubtitleCreated(res);
  }
}

function onTendencyChange(e) {
  this.setState({tendency: e.currentTarget.value});
  e.currentTarget.checked = true;
}

function initControls() {
  if (!this.props.model) return;

  this.refs.media.getDOMNode().value = this.props.model.media_id;
  this.refs.section.getDOMNode().value = this.props.model.section;
  this.refs.page.getDOMNode().value = this.props.model.page;
  this.refs.title.getDOMNode().value = this.props.model.title;
  this.refs.subtitle.getDOMNode().value = this.props.model.subtitle;
  this.refs.gender.getDOMNode().value = this.props.model.gender;
  this.refs.topic.getDOMNode().value = this.props.model.topic_id;
  this.refs.measure.getDOMNode().value = this.props.model.measure;
  this.refs.cost.getDOMNode().value = this.props.model.cost;
  this.refs.description.getDOMNode().value = this.props.model.description;
  this.setState({tendency: this.props.model.tendency});
}

var PrintedMediaForm = React.createClass({
  getInitialState: function () {
    return {
      tendency: '1'
    };
  },
  componentDidMount: function () {
    initControls.call(this);
  },
  getData: function() {
    var data = {};
    if (this.props.model) {
      data.id = this.props.model.id;
    }
    data.type = 1;
    data.media_id = this.refs.media.getDOMNode().value;
    data.section = this.refs.section.getDOMNode().value;
    data.page = this.refs.page.getDOMNode().value;
    data.title = this.refs.title.getDOMNode().value;
    data.subtitle = this.refs.subtitle.getDOMNode().value;
    data.gender = this.refs.gender.getDOMNode().value;
    data.topic_id = this.refs.topic.getDOMNode().value || null;
    data.measure = this.refs.measure.getDOMNode().value;
    data.cost = this.refs.cost.getDOMNode().value;
    data.tendency = this.state.tendency;
    data.description = this.refs.description.getDOMNode().value;

    return data;
  },
  render: function() {
    var media = this.props.media.map(function(item) {
      if(item.type == 1)
        return <option value={item.id}>{item.name}</option>
    });
    var topics = this.props.topics.map(function(item) {
      return <option value={item.id}>{item.name}</option>;
    });
    var subtitles = this.props.subtitles.map(function(item) {
      var mark = '';
      if(this.props.model) {
        mark = item.subtitle === this.props.model.subtitle ? 'selected' : '';
      }
      return <option value={item.subtitle} selected={mark}>{item.subtitle}</option>;
    }.bind(this));
    return (
      <div className="row">
        <div className="col-md-12">
          <ModalSubtitleCreateForm ref="subtitleModal" onSubtitleCreated={onSubtitleCreated.bind(this)} />
          <div className="section-divider"><span>IMPRESO</span></div>
          <div className="row">
            <div className="col-md-5">
              <select ref="media" className="form-control">
                <option value="">--- Seleccion Medio ---</option>
                {media}
              </select>
            </div>
            <div className="col-md-1">
              <a className="btn btn-light btn-add" href="/dashboard/media/create">
                <i className="fa fa-plus"></i>
              </a>
            </div>
            <div className="col-md-3">
              <div className="form-group">
                <div className="input-group">
                  <div className="input-group-addon">
                    <i className="fa fa-envelope"></i>
                  </div>
                  <input type="text" ref="section" className="form-control" placeholder="Sección" />
                </div>
              </div>
            </div>
            <div className="col-md-3">
              <div className="form-group">
                <div className="input-group">
                  <div className="input-group-addon">
                    <i className="fa fa-search"></i>
                  </div>
                  <input type="text" ref="page" className="form-control" placeholder="Página" />
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
                  <input type="text" ref="title" className="form-control" placeholder="Título" />
                </div>
              </div>
            </div>
            <div className="col-md-4">
              <div className="form-group">
                <select ref="subtitle" className="form-control">
                  {subtitles}
                </select>
              </div>
            </div>
            <div className="col-md-1">
              <a className="btn btn-light btn-add" href="javascript:void(0)" onClick={displayModal.bind(this)}>
                <i className="fa fa-plus"></i>
              </a>
            </div>
          </div>
          <div className="row">
            <div className="col-md-5">
              <select ref="topic" className="form-control">
                <option value="">--- Seleccione Tema ---</option>
                {topics}
              </select>
            </div>
            <div className="col-md-1">
              <a className="btn btn-light btn-add" href="/dashboard/topics/create">
                <i className="fa fa-plus"></i>
              </a>
            </div>
            <div className="col-md-5">
              <div className="form-group">
                <div className="input-group">
                  <div className="input-group-addon">
                    <i className="fa fa-user"></i>
                  </div>
                  <input type="text" ref="gender" className="form-control" placeholder="Género" />
                </div>
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <div className="clasification">
                Tendencia&nbsp;&nbsp;&nbsp;
                <label>
                  <input type="radio" name="tendency_printed" value="1"
                    onChange={onTendencyChange.bind(this)}
                    checked={this.state.tendency === '1'} />
                  Positiva
                </label>
                &nbsp;&nbsp;
                <label>
                  <input type="radio" name="tendency_printed" value="2"
                    onChange={onTendencyChange.bind(this)}
                    checked={this.state.tendency === '2'} />
                  Negativa
                </label>
                &nbsp;&nbsp;
                <label>
                  <input type="radio" name="tendency_printed" value="3"
                    onChange={onTendencyChange.bind(this)}
                    checked={this.state.tendency === '3'} />
                  Neutra
                </label>
              </div>
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


module.exports = PrintedMediaForm;
