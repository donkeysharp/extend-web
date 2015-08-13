'use strict';
var Topo = require('./Topo.jsx');
var Papu = require('./Papu.jsx');
var MyApp = {};


var Person = function(name) {
  this.name = name || 'no name';
};
Person.prototype = {
  sayHello: function() {
    console.log("hello my name is", this.name);
  }
};

MyApp.Person = Person;

MyApp.Pene = '<h1>aaaa</h1>';
MyApp.Topo = Topo;

module.exports = MyApp;
module.exports.fucking = function() {
  return {
    foobar: 'meg'
  };
};

