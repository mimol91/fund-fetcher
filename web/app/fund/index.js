var fund = angular.module('fund', []);

fund.state = {
    template: require('./tpl/list.html'),
    controller: require('./controllers/fund.js')
};

fund.factory('FundRepository', require('./services/repository.js'));
fund.filter('emptyToEnd', require('./services/filter-empty.js'));

module.exports = fund;
