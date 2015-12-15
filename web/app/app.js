var fund = require('./fund');

var app = angular.module('app', [
    'ui.router',
    'fund'
]);

app.config(function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/');

    $stateProvider
        .state('main', {
            url: '/',
            views: {
                'content@': fund.state
            }
        });
});
