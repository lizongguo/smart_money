/**
 * 格式化时间
 * @param {type} fmt
 * @returns {unresolved}
 */
Date.prototype.Format = function(fmt) {
    var o = {
        "M+": this.getMonth() + 1, //月
        "d+": this.getDate(), //日
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(), 
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S": this.getMilliseconds() 
    };
    if (/(y+)/.test(fmt)){
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    for (var k in o){
        if (new RegExp("(" + k + ")").test(fmt)){
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        }
    }
    return fmt;
};

/**
 * ajax请求
 * @param {string} url
 * @param {array} params
 * @param {function|null} successCallback
 * @param {function|null} errorCallback
 * @param {false|null} async
 * @returns void
 */
function ajaxApi (url, params, successCallback, errorCallback, async)
{
    if(!params) {
        params = {};
    }
    if (typeof async == 'undefined') {
        async = true;
    };
    $.ajax({
        type: "post",
        url: url,
        dataType: "json",
        data: params,
        async: async,
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Accept", "application/json");
        },
        success: function(data){
            if(data.status == '410') {
                //清除本地缓存 用户情报
                location.href='/login';
                return ;
            }
            if(typeof successCallback == 'function') {
                successCallback(data);
            }
        },
        error: function (err){
            console.log(err)
            response = JSON.parse(err.responseText);
            if(typeof errorCallback == 'function') {
                errorCallback(response);
            }
        }
    });
}


