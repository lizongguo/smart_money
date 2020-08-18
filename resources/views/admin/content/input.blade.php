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
        <label class="layui-form-label">标题<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[title]" value="{{$data->title}}" lay-verify="required" autocomplete="off" class="layui-input" >
        </div>
    </div>
  
    <div class="layui-form-item">
        <label class="layui-form-label">类型<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select name="data[type]" lay-verify="required" lay-filter="test" id="seltype"> 
                <option value="">请选择</option>
                @foreach(config("code.buttom_type") as $k => $v)
                <option value="{{$k}}" @if($data->type == $k) selected @endif >{{$v}}</option>
                @endforeach
            </select> 
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <textarea name="data[content]" lay-verify="content" id="content" style="display: none;">{{ $data->content }}</textarea>
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
}).use(['index', 'form', 'formSelects', 'upload','layedit','element'], function () {
var $ = layui.$,
        admin = layui.admin,
        form = layui.form,
        upload = layui.upload,
        formSelects = layui.formSelects;

        var layedit = layui.layedit;
        var element = layui.element;
        var cont_build = layedit.build('content');

        form.verify({
            content: function(value) {
                return layedit.sync(cont_build);
            }
        });

})
</script>
@endsection