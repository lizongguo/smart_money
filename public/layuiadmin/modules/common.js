/**

 @Name：layuiAdmin 公共业务
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */
 
layui.define('form', function(exports){
  var $ = layui.$
  ,layer = layui.layer
  ,laytpl = layui.laytpl
  ,setter = layui.setter
  ,view = layui.view
  ,upload = layui.upload
  ,form = layui.form
  ,admin = layui.admin
  
  //公共业务的逻辑处理可以写在此处，切换任何页面都会执行
  
    //上传图片空间
    admin.uploadImage = function(id_prefix) {
          var uploadId = id_prefix+ "_upload";
          var showId = id_prefix+ "_show";
          var srcId = id_prefix+ "_src";

          console.log(3333);

          var obj = $('#'+uploadId).data('obj');
          console.log(obj);
          var type = $('#' + srcId).is('input') ?  'input' : ($('#' + srcId).is('img') ? 'img' :  '');
          console.log(type);
          var url = setter.uriHost.replace(/\/admin[\/]?$/, '') + "/api/upload/" + obj;
          //上传头像
          var avatarSrc = $('#'+srcId);
          upload.render({
              url: url
              , elem: '#' + uploadId
              , field: 'attachment'
              , accept: 'file'
              , done: function (res) {
                  if (res.status == 200) {
                      layer.msg(res.msg, {icon: 6});
                      
                      var src_path =  res.data.thumb_path ? res.data.thumb_path : res.data.file_path; //(setter.uriHost.replace(/admin\/$/i, '')) + res.data.file_path.replace(/^\//i, '');
                      if (type == 'img') {
                          avatarSrc.attr('src', src_path);
                      }else {
                          avatarSrc.val(src_path);
                      }
                  } else {
                      layer.msg(res.msg, {icon: 5});
                  }
              }
          });
          //查看图片
          $('#'+showId).on('click', function(){
              if (type == 'img') {
                  var src = avatarSrc.attr('src');
              }else {
                  var src = avatarSrc.val();
              }
              console.log(src);
              if (/\.(png|jpg|jpeg|hif)$/i.test(src)) {
                layer.photos({
                    photos: {
                        "title": "查看头像" //相册标题
                        , "data": [{
                                "src": setter.uriHost.replace(/\/admin[\/]?$/, '') + src //原图地址
                            }]
                    }
                    , shade: 0.01
                    , closeBtn: 1
                    , anim: 5
                });
            }else if(/\.(mp4)$/i.test(src)) {
                if(layui.index){
                    layui.index.openTabsPage(setter.uriHost.replace(/\/admin[\/]?$/, '') + src, "视频预览")
                }else{
                    window.open(src)
                }
            } 
          });
      }

    admin.uploadFile = function(id_prefix) {
          var uploadId = id_prefix+ "_upload";
          var showId = id_prefix+ "_show";
          var srcId = id_prefix+ "_src";

          var obj = $('#'+uploadId).data('obj');
          var type = $('#' + srcId).is('input') ?  'input' : ($('#' + srcId).is('img') ? 'img' :  '');

          var url = setter.uriHost.replace(/\/admin[\/]?$/, '') + "/api/fileupload/" + obj;
          //上传头像
          var avatarSrc = $('#'+srcId);
          upload.render({
              url: url
              , elem: '#' + uploadId
              , field: 'attachment'
              , accept: 'file'
              , done: function (res) {
                  if (res.status == 200) {
                      layer.msg(res.msg, {icon: 6});
                      
                      var src_path =  res.data.thumb_path ? res.data.thumb_path : res.data.file_path; //(setter.uriHost.replace(/admin\/$/i, '')) + res.data.file_path.replace(/^\//i, '');
                      if (type == 'img') {
                          avatarSrc.attr('src', src_path);
                      }else {
                          avatarSrc.val(src_path);
                      }

                      //回填文件名
                      var filePath = res.data.file_name;
                      var startIndex = filePath.lastIndexOf(".");
                      if(startIndex != -1) {
                          var src_path_name = filePath.substring(0, startIndex).toLowerCase();
                          $("input[name='data[name]']").val(src_path_name);
                      } else {
                          var src_path_name = "";
                      }
                  } else {
                      layer.msg(res.msg, {icon: 5});
                  }
              }
          });
      }


    //检测上传图片控件
    $('.uploadImage').each(function(){
        var prefix = $(this).data('prefix');
        if (!!prefix) {
            admin.uploadImage(prefix);
        }
    });

    $('.uploadFile').each(function(){
        var prefix = $(this).data('prefix');
        if (!!prefix) {
            admin.uploadFile(prefix);
        }
    });
    
    //验证规则
    form.verify({
        //我们既支持上述函数式的方式，也支持下述数组的形式
        //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
        pass: [
            /^((\s*)|([\S]{6,12}))$/,
            '密码必须6到12位，且不能出现空格'
        ]
        //确认密码        
        , repass: function (value) {
            if (value !== $('#LAY_password').val()) {
                return '两次密码输入不一致';
            }
        }
        , nickname: function (value, item) { //value：表单的值、item：表单的DOM对象
            if (!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)) {
                return '用户名不能有特殊字符';
            }
            if (/(^\_)|(\__)|(\_+$)/.test(value)) {
                return '用户名首尾不能出现下划线\'_\'';
            }
            if (/^\d+\d+\d$/.test(value)) {
                return '用户名不能全为数字';
            }
        }
        , name: function (value, item) { //value：表单的值、item：表单的DOM对象
            if (!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)) {
                return '用户名不能有特殊字符';
            }
            if (/(^\_)|(\__)|(\_+$)/.test(value)) {
                return '用户名首尾不能出现下划线\'_\'';
            }
            if (/^\d+\d+\d$/.test(value)) {
                return '用户名不能全为数字';
            }
        }
        , captcha: [
            /^[\w]{4}$/,
            '验证码4位数字字母组成，且不能出现空格'
        ]
    });
    
  //退出
  admin.events.logout = function(){
      console.log(setter);
      layer.open({
          content: '确认退出当前账号？',
          btn: ['退出', '取消'],
          yes: function(i){
              location.href = setter.uriHost + 'logout';
          }
      });
      return ;
    //执行退出接口
    admin.req({
      url: layui.setter.base + 'admin/logout'
      ,type: 'get'
      ,data: {}
      ,done: function(res){ //这里要说明一下：done 是只有 response 的 code 正常才会执行。而 succese 则是只要 http 为 200 就会执行
        
        //清空本地记录的 token，并跳转到登入页
        admin.exit(function(){
          location.href = 'user/login.html';
        });
      }
    });
  };

  
  
  
  
  
  //对外暴露的接口
  exports('common', {});
});