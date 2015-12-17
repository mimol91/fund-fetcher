module.exports = function() {
    return {
        parseToChart: function (data) {
            return data.map(function(fundData){
                return [
                    1000* fundData.date,
                    fundData.price
                ];
            });
        }
    };
};
