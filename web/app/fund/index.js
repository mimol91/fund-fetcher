var fund = angular.module('fund', []);

fund.state = {
    template: require('./tpl/list.html'),
    controller: require('./controllers/fund.js')
};

module.exports = fund;
