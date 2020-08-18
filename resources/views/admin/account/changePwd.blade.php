@extends('layouts.admincontent')
@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">修改密码</div>
          <div class="layui-card-body" pad15>
            
            <div class="layui-form" lay-filter="">
              {{csrf_field()}}
              <div class="layui-form-item">
                <label class="layui-form-label">当前密码</label>
                <div class="layui-input-inline">
                  <input type="password" name="data[pwd]" lay-verify="required|pass" lay-verType="tips" class="layui-input">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">新密码</label>
                <div class="layui-input-inline">
                  <input type="password" name="data[pwd1]" lay-verify="pass" lay-verType="tips" autocomplete="off" id="LAY_password" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">6到12个字符</div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">确认新密码</label>
                <div class="layui-input-inline">
                  <input type="password" name="data[pwd2]" lay-verify="repass" lay-verType="tips" autocomplete="off" class="layui-input">
                </div>
              </div>
              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="setmypass">确认修改</button>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<script>
layui.config({
    uriHost: '{{asset("/admin")}}/',      //项目管理端path
    base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
}).extend({
    index: 'lib/index' //主入口模块
}).use(['index', 'form'], function(){
    var $ = layui.$
    ,setter = layui.setter
    ,admin = layui.admin
    ,form = layui.form
    ,router = layui.router()
    ,response = setter.response
    ,upload = layui.upload
    ,search = router.search;
    
    form.verify({
    //我们既支持上述函数式的方式，也支持下述数组的形式
    //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
    pass: [
      /^[\S]{6,12}$/
      ,'密码必须6到12位，且不能出现空格'
    ]
    //确认密码
    ,repass: function(value){
      if(value !== $('#LAY_password').val()){
        return '两次密码输入不一致';
      }
    }
  });
    //修改密码
    form.on('submit(setmypass)', function(obj){
//      layer.msg(JSON.stringify(obj.field));
      //提交修改
        admin.req({
          url: "{{ route('account.changePwd') }}"
          ,data: obj.field
          ,type: 'post'
          ,success: function(res){

          }
          ,done: function(res){
            //成功的提示
            layer.msg(res.msg, {
              offset: '15px'
              ,icon: 1
              ,time: 1500
            }, function(){
              location.reload();
            });
          }
        });
    });
});
</script>
@endsection
