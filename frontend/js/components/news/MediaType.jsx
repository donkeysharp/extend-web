'use strict';
var React = window.React;

function onMediaTypeChanged(e) {
  if(this.props.onChange) {
    this.props.onChange({
      mediaType: e.currentTarget.value,
      value: e.currentTarget.checked
    });
  }
}

var MediaType  = React.createClass({
  render: function () {
    return (
      <div className="row">
        <div className="col-md-12">
          Tipo:&nbsp;&nbsp;&nbsp;
          <label>
            <input type="checkbox" value="printed" onChange={onMediaTypeChanged.bind(this)} />
            &nbsp;Impreso&nbsp;&nbsp;&nbsp;
          </label>
          <label>
            <input type="checkbox" value="digital" onChange={onMediaTypeChanged.bind(this)} />
            &nbsp;Digital&nbsp;&nbsp;&nbsp;
          </label>
          <label>
            <input type="checkbox" value="radio" onChange={onMediaTypeChanged.bind(this)} />
            &nbsp;Radio&nbsp;&nbsp;&nbsp;
          </label>
          <label>
            <input type="checkbox" value="tv" onChange={onMediaTypeChanged.bind(this)} />
            &nbsp;TV&nbsp;&nbsp;&nbsp;
          </label>
          <label>
            <input type="checkbox" value="source" onChange={onMediaTypeChanged.bind(this)} />
            &nbsp;Fuente&nbsp;&nbsp;&nbsp;
          </label>
        </div>
      </div>
    );
  }
});

module.exports = MediaType;

