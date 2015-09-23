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
  changeStatus: function (data) {
    if(data) {
      if(data.printed) {
        this.refs.printed.getDOMNode().checked = true;
      }
      if(data.digital) {
        this.refs.digital.getDOMNode().checked = true;
      }
      if(data.radio) {
        this.refs.radio.getDOMNode().checked = true;
      }
      if(data.tv) {
        this.refs.tv.getDOMNode().checked = true;
      }
      // if(data.source) {
      //   this.refs.source.getDOMNode().checked = true;
      // }
    }
  },
  render: function () {
    return (
      <div className="row">
        <div className="col-md-12">
          Tipo:&nbsp;&nbsp;&nbsp;
          <label>
            <input type="checkbox" value="printed" ref="printed" onChange={onMediaTypeChanged.bind(this)} />
            &nbsp;Impreso&nbsp;&nbsp;&nbsp;
          </label>
          <label>
            <input type="checkbox" value="digital" ref="digital" onChange={onMediaTypeChanged.bind(this)} />
            &nbsp;Digital&nbsp;&nbsp;&nbsp;
          </label>
          <label>
            <input type="checkbox" value="radio" ref="radio" onChange={onMediaTypeChanged.bind(this)} />
            &nbsp;Radio&nbsp;&nbsp;&nbsp;
          </label>
          <label>
            <input type="checkbox" value="tv" ref="tv" onChange={onMediaTypeChanged.bind(this)} />
            &nbsp;TV&nbsp;&nbsp;&nbsp;
          </label>
        </div>
      </div>
    );
  }
});

module.exports = MediaType;

