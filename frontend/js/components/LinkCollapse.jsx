'use strict';

function onCollapseClick(e) {
  $(this.refs.collapseContainer.getDOMNode()).collapse('toggle');
}

var LinkCollapse = React.createClass({
  displayName: 'LinkCollapse',
  componentDidMount: function () {
    $(this.refs.collapseContainer.getDOMNode()).collapse({toggle: false});
  },
  render: function () {
    var content = this.props.content;
    var linkText = this.props.linkText || 'Expand';
    return (
      <div>
        <a href="javascript:void(0)" onClick={onCollapseClick.bind(this)}>
          {linkText}
        </a>
        <div className="collapse" ref="collapseContainer">
          <div className="well">
            {content}
          </div>
        </div>
      </div>
    );
  }
});

module.exports = LinkCollapse;
