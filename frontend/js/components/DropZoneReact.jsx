var Dropzone = window.Dropzone;

function getDefaultProps(props) {
  var data = {
    url: props.url || '/upload',
    acceptedFiles: props.acceptedFiles || 'image/*',
    maxFilesize: props.maxFilesize || 1,
    dictDefaultMessage: props.dictDefaultMessage || 'Arrastrar archivos o hacer click para subir archivos'
  };

  return data;
}

var DropzoneReact = React.createClass({
  displayName: 'DropzoneReact',
  getInitialState: function () {
    return {
      uploader: null
    };
  },
  componentDidMount: function () {
    var el = this.refs.uploader.getDOMNode();
    var dropzone = new Dropzone(el, getDefaultProps(this.props));
    if(this.props.onAddedFile) {
      dropzone.on('addedfile', this.props.onAddedFile);
    }
    this.setState({uploader: dropzone});
  },
  render: function () {
    return (
      <div ref="uploader" className="dropzone"></div>
    );
  }
});

module.exports = DropzoneReact;
