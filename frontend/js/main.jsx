var NewsEditor = require('./components/news/NewsEditor.jsx');
var $http = require('./http');
var Report = require('./reports/reports.jsx');
var MyApp = {};


MyApp.NewsEditor = NewsEditor;
MyApp.$http = $http;
MyApp.Report = Report;

module.exports = MyApp;
