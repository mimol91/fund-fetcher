module.exports = function($scope, FundRepository) {
    $scope.funds = null;
    $scope.activeId = null;

    FundRepository.getList().then(function(fundList) {
        $scope.funds = fundList;
        $scope.sortType = 'name';
    });

    $scope.toggleActiveId = function(externalId) {
        if ($scope.activeId == externalId) {
            $scope.activeId = null;
        } else {
            $scope.activeId = externalId;
            $scope.$broadcast('fund:details', externalId);
        }
    };
};
