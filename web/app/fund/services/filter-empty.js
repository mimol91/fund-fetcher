module.exports =  function() {
  var accessProperties = function(object, string) {
    var explodedString = string.split('.');
    for (var i = 0, l = explodedString.length; i < l; i++) {
      object = object[explodedString[i]];
    }

    return object;
  };

  return function(array, key) {
    if (!angular.isArray(array)) { return; }

    if (!key) { return; }

    var present = array.filter(function(item) {
      return accessProperties(item, key);
    });

    var empty = array.filter(function(item) {
      return !item[key];
    });

    return present.concat(empty);
  };
};
