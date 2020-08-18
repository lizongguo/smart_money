/**

 @Name：layuiAdmin 用户登入和注册等
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License: LPPL
    
 */
 
layui.define('form', function(exports){
  var $ = layui.$
  ,layer = layui.layer
  ,laytpl = layui.laytpl
  ,setter = layui.setter
  ,view = layui.view
  ,admin = layui.admin
  ,form = layui.form;

  var $body = $('body');
  
  //发送短信验证码
  admin.sendAuthCode({
    elem: '#LAY-user-getsmscode'
    ,elemPhone: '#LAY-user-login-cellphone'
    ,elemVercode: '#LAY-user-login-vercode'
    ,ajax: {
      url: layui.setter.base + 'json/user/sms.js' //实际使用请改成服务端真实接口
    }
  });
  
  
  
  
  //更换图形验证码
  $body.on('click', '#LAY-user-get-vercode', function(){
    var othis = $(this);
    this.src = this.src.replace(/\&t.*$/, '') + '&t='+ new Date().getTime()
  });
  
  //对外暴露的接口
  exports('user', {});
});