module.exports = function($scope, FundRepository) {
    $scope.funds = null;
    $scope.activeId = null;

    FundRepository.getList().then(function(fundList) {
        $scope.funds = fundList;
        $scope.sortType = 'name';
    });

    $scope.getDetails = function(externalId) {
        var fund = _.find($scope.funds, 'externalId', externalId);

        FundRepository.get(fund.id).then(function(fundData){
            fund.data = fundData.fundDataCollection || [];
        });
    };

    $scope.toggleActiveId = function(externalId) {
        if ($scope.activeId == externalId) {
            $scope.activeId = null;
        } else {
            $scope.activeId = externalId;
            $scope.getDetails(externalId);
        }
    };
};
