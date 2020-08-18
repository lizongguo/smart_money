@extends('layouts.admin')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/formSelects-v4.css')}}"/>
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">账号<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="text" name="data[email]" value="{{$data->email}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">用于前台用户登入名</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码@if(!isset($data->id))<font style="color: red">*</font>@endif</label>
        <div class="layui-input-inline">
            <input type="password" name="data[password]" value="" @if(!isset($data->id)) lay-verify="required|pass" @else lay-verify="pass" @endif  autocomplete="off" class="layui-input">
        </div>
        @if(isset($data->id))
        <div class="layui-form-mid layui-word-aux">不输入密码表示不修改</div>
        @endif
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-inline">
            <input type="text" name="data[email_info]" value="{{$data->email_info}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">权限</label>
        <div class="layui-input-inline" style="width: 70%">
            <select xm-select="permissions" xm-select-direction="default" xm-select-height="150px" xm-select-show-count="6"  name="data[permissions]" >
                <option value="">请选择</option>
                @foreach(config("code.found_role") as $k => $v)
                <option value="{{$k}}" @if(in_array($k, $data->permissions)) selected @endif >{{$v}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-inline">
            <input type="text" name="data[phone]" value="{{$data->phone}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮寄地址</label>
        <div class="layui-input-inline">
            <input type="text" name="data[address]" value="{{$data->address}}" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">电话号码</label>
        <div class="layui-input-inline">
            <input type="text" name="data[tel_phone]" value="{{$data->tel_phone}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">收件人</label>
        <div class="layui-input-inline">
            <input type="text" name="data[recipient]" value="{{$data->recipient}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-inline">
            <input type="text" name="data[note]" value="{{$data->note}}" autocomplete="off" class="layui-input">
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