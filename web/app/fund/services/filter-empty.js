module.exports =  function() {
    var accessProperties = function(object, string){
        var explodedString = string.split('.') ;
        for (i = 0, l = explodedString.length; i<l; i++){
            object = object[explodedString[i]];
        }

        return object;
    };

    return function (array, key) {
        if (!angular.isArray(array)) { return ; }
        if (!key) { return; }

        return array.filter(function (item) {
            return accessProperties(item, key);
        });
    };
};