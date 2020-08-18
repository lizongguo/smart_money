@extends('layouts.admin')
@section('content')
<style type="text/css">
    
    .layui-input-inline {
        width: 300px;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/formSelects-v4.css')}}"/>
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">基金现用名<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[current_name]" value="{{$data->current_name}}" lay-verify="required" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">基金曾用名</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[ever_name]" value="{{$data->ever_name}}" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">现任执行GP</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[current_gp]" value="{{$data->current_gp}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">币种<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select name="data[currency]" lay-verify="required" lay-filter="test" id="seltype"> 
                <option value="">请选择</option>
                @foreach(config("code.found_currency") as $k => $v)
                <option value="{{$k}}" @if($data->currency == $k) selected @endif >{{$v}}</option>
                @endforeach
            </select> 
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">基金总额<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="text" name="data[total_value_cn]" value="{{$data->total_value_cn}}" autocomplete="off" class="layui-input" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">关系图</label>
        <div class="layui-input-inline" style="width: 50%">
            <input name="data[image]" id="LAY_image_src" placeholder="图片地址" value="@if($data->image){{ $data->image}}@endif" class="layui-input">
        </div>
        <div class="layui-input-block layui-btn-container" style="width: auto;">
            <button type="button" class="layui-btn layui-btn-primary uploadImage" id="LAY_image_upload" data-prefix="LAY_image" data-obj="adminAvatar">
                <i class="layui-icon">&#xe67c;</i>上传图片
            </button>
            <button class="layui-btn layui-btn-primary" id="LAY_image_show">查看图片</button >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">基金简介</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[introduce]" style="height: 100px;" placeholder="基金简介" autocomplete="off" lay-verify="required" class="layui-textarea">{{$data->introduce}}</textarea>
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