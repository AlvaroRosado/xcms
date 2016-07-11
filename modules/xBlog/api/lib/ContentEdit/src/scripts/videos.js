// Generated by CoffeeScript 1.10.0
var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

ContentEdit.Video = (function(superClass) {
  extend(Video, superClass);

  function Video(tagName, attributes, sources) {
    var size;
    if (sources == null) {
      sources = [];
    }
    Video.__super__.constructor.call(this, tagName, attributes);
    this.sources = sources;
    size = this.size();
    this._aspectRatio = size[1] / size[0];
  }

  Video.prototype.cssTypeName = function() {
    return 'video';
  };

  Video.prototype.type = function() {
    return 'Video';
  };

  Video.prototype.typeName = function() {
    return 'Video';
  };

  Video.prototype._title = function() {
    var src;
    src = '';
    if (this.attr('src')) {
      src = this.attr('src');
    } else {
      if (this.sources.length) {
        src = this.sources[0]['src'];
      }
    }
    if (!src) {
      src = 'No video source set';
    }
    if (src.length > ContentEdit.HELPER_CHAR_LIMIT) {
      src = text.substr(0, ContentEdit.HELPER_CHAR_LIMIT);
    }
    return src;
  };

  Video.prototype.createDraggingDOMElement = function() {
    var helper;
    if (!this.isMounted()) {
      return;
    }
    helper = Video.__super__.createDraggingDOMElement.call(this);
    helper.innerHTML = this._title();
    return helper;
  };

  Video.prototype.html = function(indent) {
    var attributes, i, len, ref, source, sourceStrings;
    if (indent == null) {
      indent = '';
    }
    if (this.tagName() === 'video') {
      sourceStrings = [];
      ref = this.sources;
      for (i = 0, len = ref.length; i < len; i++) {
        source = ref[i];
        attributes = ContentEdit.attributesToString(source);
        sourceStrings.push("" + indent + ContentEdit.INDENT + "<source " + attributes + ">");
      }
      return (indent + "<video" + (this._attributesToString()) + ">\n") + sourceStrings.join('\n') + ("\n" + indent + "</video>");
    } else {
      return (indent + "<" + this._tagName + (this._attributesToString()) + ">") + ("</" + this._tagName + ">");
    }
  };

  Video.prototype.mount = function() {
    var style;
    this._domElement = document.createElement('div');
    if (this.a && this.a['class']) {
      this._domElement.setAttribute('class', this.a['class']);
    } else if (this._attributes['class']) {
      this._domElement.setAttribute('class', this._attributes['class']);
    }
    style = this._attributes['style'] ? this._attributes['style'] : '';
    if (this._attributes['width']) {
      style += "width:" + this._attributes['width'] + "px;";
    }
    if (this._attributes['height']) {
      style += "height:" + this._attributes['height'] + "px;";
    }
    this._domElement.setAttribute('style', style);
    this._domElement.setAttribute('data-ce-title', this._title());
    return Video.__super__.mount.call(this);
  };

  Video.droppers = {
    'Image': ContentEdit.Element._dropBoth,
    'PreText': ContentEdit.Element._dropBoth,
    'Static': ContentEdit.Element._dropBoth,
    'Text': ContentEdit.Element._dropBoth,
    'Video': ContentEdit.Element._dropBoth
  };

  Video.placements = ['above', 'below', 'left', 'right', 'center'];

  Video.fromDOMElement = function(domElement) {
    var c, childNode, childNodes, i, len, sources;
    childNodes = (function() {
      var i, len, ref, results;
      ref = domElement.childNodes;
      results = [];
      for (i = 0, len = ref.length; i < len; i++) {
        c = ref[i];
        results.push(c);
      }
      return results;
    })();
    sources = [];
    for (i = 0, len = childNodes.length; i < len; i++) {
      childNode = childNodes[i];
      if (childNode.nodeType === 1 && childNode.tagName.toLowerCase() === 'source') {
        sources.push(this.getDOMElementAttributes(childNode));
      }
    }
    return new this(domElement.tagName, this.getDOMElementAttributes(domElement), sources);
  };

  return Video;

})(ContentEdit.ResizableElement);

ContentEdit.TagNames.get().register(ContentEdit.Video, 'iframe', 'video');