module.exports = function($scope, FundRepository) {
    $scope.funds = null;

    FundRepository.getList().then(function(fundList) {
        $scope.funds = fundList;
        $scope.sortType = 'name';

    });
};
