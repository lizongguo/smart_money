var utils = module.exports;

/**
 * Check and invoke callback function
 */
utils.invokeCallback = function(cb) {
  var args = arguments;
  if(!!cb && typeof cb === 'function') {
  	process.nextTick(function(){
      cb.apply(null, Array.prototype.slice.call(args, 1));
  	});
  }
};

utils.currentTime = function() {
	return Math.round(new Date().getTime()/1000) ;
};

var prefix = 'ordering:';

utils.genKey = function(key) {
	return prefix + key;
}

utils.in_array = function (search, array, key){
    var item = null;
    for(var i in array){
        if(key){
            item = array[i][key];
        }else{
            item = array[i];
        }
        if(item==search){
            return true;
        }
    }
    return false;
};
