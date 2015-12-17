module.exports = function(FundRepository, FundDataParser) {
    return {
        restrict: 'A',
        template: require('../tpl/details.html'),
        scope: {
            fund: '='
        },
        link: function($scope, element) {
            var el = $(element);
            var getDetails = function() {
                if ($scope.fund.data) { return; }

                FundRepository.get($scope.fund.id).then(function(fundData) {
                    $scope.fund.data = fundData.fundDataCollection || [];

                    //Hack to allows highcharts calculate valid graph size ?
                    setTimeout(function() {
                        el.highcharts('StockChart', {
                            rangeSelector: {selected: 1},
                            title: {text: $scope.fund.name},
                            series: [{
                                name: $scope.fund.name,
                                data: FundDataParser.parseToChart($scope.fund.data),
                                tooltip: {valueDecimals: 2}
                            }]
                        });
                    }, 0);
                });
            };

            $scope.$on('fund:details', function(event, externalId) {
                if ($scope.fund.externalId === externalId) {
                    getDetails();
                }
            });
        }
    };
};
