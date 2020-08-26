@extends('layouts.admincontent')
@section('content')
<link rel="stylesheet" href="{{asset('/layuiadmin/style/login.css')}}" media="all">
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
    <div class="layadmin-user-login-main">
      <div class="layadmin-user-login-box layadmin-user-login-header">
        <h2>{{config('site.sitename', '内容')}}</h2>
      </div>
      <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        {{csrf_field()}}
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
          <input type="text" name="email" id="LAY-user-login-username" lay-verify="required|email" placeholder="账号" class="layui-input">
        </div>
          
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
          <input type="password" name="password" id="LAY-user-login-password" lay-verify="required|pass" placeholder="密码" class="layui-input">
        </div>
        <div class="layui-form-item" style="margin-bottom: 20px;">
          <input type="checkbox" name="remember" value='1' lay-skin="primary" title="记住密码">
          {{--<a href="forget.html" class="layadmin-user-jump-change layadmin-link" style="margin-top: 7px;">忘记密码？</a>--}}
        </div>
        <div class="layui-form-item">
          <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-login-submit">登录（自动部署测试2）</button>
        </div>
        <div class="layui-trans layui-form-item layadmin-user-login-other">
<!--          <label>社交账号登入</label>
          <a href="javascript:;"><i class="layui-icon layui-icon-login-qq"></i></a>
          <a href="javascript:;"><i class="layui-icon layui-icon-login-wechat"></i></a>
          <a href="javascript:;"><i class="layui-icon layui-icon-login-weibo"></i></a>-->
          
          {{--<a href="reg.html" class="layadmin-user-jump-change layadmin-link">注册帐号</a>--}}
        </div>
      </div>
    </div>
    
    <div class="layui-trans layadmin-user-login-footer">
      
      <p> <a href="javascript:void(0)" target="_blank"></a></p>
      <p>
<!--        <span><a href="http://www.layui.com/admin/#get" target="_blank">获取授权</a></span>
        <span><a href="http://www.layui.com/admin/pro/" target="_blank">在线演示</a></span>
        <span><a href="http://www.layui.com/admin/" target="_blank">前往官网</a></span>-->
      </p>
    </div>
    
    <div class="ladmin-user-login-theme">
<!--      <script type="text/html" template>
        <ul>
          <li data-theme=""><img src="{{asset("/layuiadmin/style/res/bg-none.jpg")}}"></li>
          <li data-theme="#03152A" style="background-color: #03152A;"></li>
          <li data-theme="#2E241B" style="background-color: #2E241B;"></li>
          <li data-theme="#50314F" style="background-color: #50314F;"></li>
          <li data-theme="#344058" style="background-color: #344058;"></li>
          <li data-theme="#20222A" style="background-color: #20222A;"></li>
        </ul>
      </script>-->
    </div>
    
  </div>

  <script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>  
  <script>
  layui.config({
    base: '{{asset("/layuiadmin/")}}/', //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use(['index', 'user'], function(){
    var $ = layui.$
    ,setter = layui.setter
    ,admin = layui.admin
    ,form = layui.form
    ,router = layui.router()
    ,response = setter.response
    ,search = router.search;
    form.render();
    //提交
    form.on('submit(LAY-user-login-submit)', function(obj){
    
      //请求登入接口
      admin.req({
        url: '{{route("admin.login")}}'
        ,data: obj.field
        ,type: 'post'
        ,success: function(res){
            if (res[response.statusName] !== response.statusCode.ok) {
                var src = $("#LAY-user-get-vercode").attr('src');
                $("#LAY-user-get-vercode").attr('src', src.replace(/\&t.*$/, '') + '&t='+ new Date().getTime());
            }
        }
        ,done: function(res){
            
            console.log(res);
          //请求成功后，写入 access_token
//          layui.data(setter.tableName, {
//            key: setter.request.tokenName
//            ,value: res.data.access_token
//          });
          
          //登入成功的提示与跳转
          layer.msg('登录成功', {
            offset: '15px'
            ,icon: 1
            ,time: 1500
          }, function(){
            location.href = "{{asset('/admin')}}"; //后台主页
          });
        }
      });
      
    });
    
    
    //实际使用时记得删除该代码
//    layer.msg('为了方便演示，用户名密码可随意输入', {
//      offset: '15px'
//      ,icon: 1
//    });
    
  });
  </script>
@endsection