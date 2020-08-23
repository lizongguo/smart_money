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
        <label class="layui-form-label">基金名称<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[name]" value="{{$data->name}}" lay-verify="required" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">基金代码</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[code]" maxlength="10" value="{{$data->code}}" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">排名</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[ranking]" maxlength="3" value="{{$data->ranking}}" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">类型<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select name="data[type]" lay-verify="required" lay-filter="test" id="seltype"> 
                <option value="">请选择</option>
                @foreach(config("code.fund_type") as $k => $v)
                <option value="{{$k}}" @if($data->type == $k) selected @endif >{{$v}}</option>
                @endforeach
            </select> 
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">基金简介</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="200" name="data[desc]" style="height: 100px;" placeholder="基金简介" autocomplete="off" class="layui-textarea">{{$data->desc}}</textarea>
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