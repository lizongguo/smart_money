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
        <label class="layui-form-label">公司名称<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[company_name]" value="{{$data->company_name}}" lay-verify="required" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">公司英文名称</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[company_name_eng]" value="{{$data->company_name_eng}}" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">公司地址</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[address]" value="{{$data->address}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">官网</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[website]" value="{{$data->website}}" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">公司状态<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select name="data[state_val]" lay-verify="required" lay-filter="test" id="seltype"> 
                <option value="">请选择</option>
                @foreach(config("code.project_state") as $k => $v)
                <option value="{{$k}}" @if($data->state_val == $k) selected @endif >{{$v}}</option>
                @endforeach
            </select> 
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态信息</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[state]" value="{{$data->state}}" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">上市市值</label>
        <div class="layui-input-inline">
            <input type="text" name="data[listed_val]" value="{{$data->listed_val}}" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">股价链接</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[shares_url]" value="{{$data->shares_url}}" autocomplete="off" class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">公司简介</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[content]" style="height: 100px;" placeholder="基金简介" autocomplete="off" class="layui-textarea">{{$data->content}}</textarea>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">项目详细</label>
        <div class="layui-input-inline" style="width: 50%">
            <input name="data[file_path]" id="LAY_file_src" placeholder="文件地址" value="@if($data->file_path){{ $data->file_path}}@endif" class="layui-input">
        </div>
        <div class="layui-input-block layui-btn-container" style="width: auto;">
            <button type="button" class="layui-btn layui-btn-primary uploadFile" id="LAY_file_upload" data-prefix="LAY_file" data-obj="projectFile">
                <i class="layui-icon">&#xe67c;</i>上传文件
            </button>
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