var fund = angular.module('fund', []);

fund.state = {
  template: require('./tpl/list.html'),
  controller: require('./controllers/fund.js')
};

fund.factory('FundRepository', require('./services/repository.js'));
fund.factory('FundDataParser', require('./services/data-parser.js'));

fund.filter('emptyToEnd', require('./services/filter-empty.js'));

fund.directive('fundDetails', require('./directives/fund-detail-directive.js'));

module.exports = fund;
