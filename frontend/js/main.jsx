var NewsEditor = require('./components/news/NewsEditor.jsx');
var $http = require('./http');
var Report = require('./reports/reports.jsx');
var CopyNewsModal = require('./components/modals/CopyNewsModal.jsx');
var MyApp = {};


MyApp.NewsEditor = NewsEditor;
MyApp.$http = $http;
MyApp.Report = Report;
MyApp.CopyNewsModal = CopyNewsModal;

module.exports = MyApp;
