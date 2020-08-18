@extends('layouts.admin')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/formSelects-v4.css')}}"/>
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">名称<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="text" name="data[name]" value="{{$data->name}}" lay-verify="required" placeholder="请输入管理员名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮箱<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="text" name="data[email]" value="{{$data->email}}" lay-verify="email" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">用于后台登入名</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码@if(!isset($data->id))<font style="color: red">*</font>@endif</label>
        <div class="layui-input-inline">
            <input type="text" name="data[password]" value="" @if(!isset($data->id)) lay-verify="required|pass" @else lay-verify="pass" @endif  autocomplete="off" class="layui-input">
        </div>
        @if(isset($data->id))
        <div class="layui-form-mid layui-word-aux">不输入密码表示不修改</div>
        @endif
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
    <div class="layui-form-item">
        <label class="layui-form-label">角色<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select name="data[role]" lay-verify="required">
                <option value="">请选择角色</option>
                @foreach($roles as $role)
                <option value="{{$role->id}}" @if($data->role == $role->id) selected @endif >{{$role->name}}</option>
                @endforeach
            </select> 
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">权限</label>
        <div class="layui-input-inline" style="width: 70%">
            <select xm-select="permissions" xm-select-direction="default" xm-select-height="150px" xm-select-show-count="6"  name="data[permissions]" >
                <option value="">请选择</option>
                @foreach($permissions as $permission)
                <option value="{{$permission->id}}" @if(in_array($permission->id, $data->permissions)) selected @endif>{{$permission->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block" style="width: 70%">
            <textarea name="data[remarks]" placeholder="请输入备注" class="layui-textarea">{{ $data->remarks }}</textarea>
        </div>
    </div>
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="LAY-input-front-submit" id="LAY-input-front-submit" value="确认">
    </div>
</div>

<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/formSelects-v4.css')}}"/>
<script>
layui.config({
uriHost: '{{asset("/admin")}}/', //项目管理端path
base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
}).extend({
index: 'lib/index', //主入口模块
formSelects: 'formSelects-v4.min' //主入口模块
}).use(['index', 'form', 'formSelects', 'upload'], function () {
var $ = layui.$,
        admin = layui.admin,
        form = layui.form,
        upload = layui.upload,
        formSelects = layui.formSelects;

})
</script>
@endsection