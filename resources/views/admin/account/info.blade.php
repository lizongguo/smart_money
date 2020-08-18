@extends('layouts.admincontent')
@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">设置我的资料</div>
          <div class="layui-card-body" pad15>
            <div class="layui-form" lay-filter="">
                {{csrf_field()}}
              <div class="layui-form-item">
                <label class="layui-form-label">我的角色</label>
                <div class="layui-input-inline">
                  <select name="data[role]" lay-verify="">
                    @foreach($roles as $role => $roleName)
                    <option value="{{$role}}" @if($data['role'] == $role) selected @else disabled @endif >{{$roleName}}</option>
                    @endforeach
                  </select> 
                </div>
                <div class="layui-form-mid layui-word-aux">当前角色不可更改为其它角色</div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">名称<font style="color: red">*</font></label>
                <div class="layui-input-inline">
                  <input type="text" name="data[name]" value="{{$data['name']}}" class="layui-input">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">邮箱<font style="color: red">*</font></label>
                <div class="layui-input-inline">
                  <input type="text" name="data[email]" value="{{$data['email']}}" lay-verify="email" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">一般用于后台登入名</div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">头像<font style="color: red">*</font></label>
                <div class="layui-input-inline" style="width: 50%">
                    <input name="data[avatar]" lay-verify="required" id="LAY_image_src" placeholder="图片地址" value="@if($data->avatar){{ $data->avatar}}@endif" class="layui-input">
                </div>
                <div class="layui-input-block layui-btn-container" style="width: auto;">
                    <button type="button" class="layui-btn layui-btn-primary uploadImage" id="LAY_image_upload" data-prefix="LAY_image" data-obj="adminAvatar">
                        <i class="layui-icon">&#xe67c;</i>上传图片
                    </button>
                    <button class="layui-btn layui-btn-primary" id="LAY_image_show">查看图片</button >
                </div>
              </div>
              <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                  <textarea name="data[remarks]" placeholder="请输入内容" class="layui-textarea">{{ $data['remarks'] }}</textarea>
                </div>
              </div>
              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="setmyinfo">确认修改</button>
                  <button type="reset" class="layui-btn layui-btn-primary">重新填写</button>
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
}).use(['index', 'form', 'upload'], function(){
    var $ = layui.$
    ,setter = layui.setter
    ,admin = layui.admin
    ,form = layui.form
    ,router = layui.router()
    ,response = setter.response
    ,upload = layui.upload
    ,search = router.search;
    //设置我的资料
    form.on('submit(setmyinfo)', function(obj){
//      layer.msg(JSON.stringify(obj.field));

      //提交修改
      //请求登入接口
        admin.req({
          url: '{{route("account.index")}}'
          ,data: obj.field
          ,type: 'post'
          ,success: function(res){

          }
          ,done: function(res){
            //登入成功的提示与跳转
            layer.msg('内容保存成功', {
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
