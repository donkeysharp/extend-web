var Dropzone = window.Dropzone;

function getDefaultProps(props) {
  var data = {
    url: props.url || '/upload',
    acceptedFiles: props.acceptedFiles || 'image/*',

  }
}

var DropzoneReact = React.createClass({
  displayName: 'DropzoneReact',
  getInitialState: function () {
    return {
      uploader = null
    };
  },
  componentDidMount: function () {
    var el = this.refs.uploader.getDOMNode();
    this.setState{uploader: new Dropzone(el, )}
  },
  render: function () {
    return (
      <div className="dropzone"></div>
    );
  }
});

module.exports = DropzoneReact;
