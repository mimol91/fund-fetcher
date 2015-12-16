module.exports = function($http) {
    return {
        getList : function() {
            var queryUrl = '/api/fund';

            return $http.get(queryUrl).then(function(res) {
                return res.data;
            });
        },
        get: function(id) {
            var queryUrl = '/api/fund/' +id ;

            return $http.get(queryUrl).then(function(res) {
                return res.data;
            });
        }
    };
};
