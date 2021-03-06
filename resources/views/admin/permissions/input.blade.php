@extends('layouts.admin')
@section('content')
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">权限名称<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="text" name="data[name]" value="{{$data->name}}" lay-verify="required" placeholder="请输入权限名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标识<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="text" name="data[slug]" value="{{$data->slug}}" lay-verify="required|slug" placeholder="请输入权限标识" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">请求方式</label>
        <div class="layui-input-inline" style="width: 70%">
            <select xm-select="permissions" lay-verify="required" xm-select-direction="down" xm-select-height="150px" xm-select-show-count="5" name="data[http_method]">
                <option value="">请选择</option>
                @foreach($httpMethods as $method)
                <option value="{{$method}}" @if(in_array($method, $data->http_method)) selected @endif>{{$method}}</option>
                @endforeach
          </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">HTTP路径<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" name="data[http_path]" style="height: 100px;" lay-verify="required"
                      placeholder="请输入HTTP路径" autocomplete="off" class="layui-textarea">{{$data->http_path}}</textarea>
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
    }).use(['index', 'form', 'formSelects'], function () {
        var $ = layui.$,
        form = layui.form;

        form.verify({
            //我们既支持上述函数式的方式，也支持下述数组的形式
            //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
            slug: [
              /^[\S]{4,}$/
              ,'标示由4个以上的非空字符组成，且不能出现空格'
            ]
         });
    })
</script>
@endsection