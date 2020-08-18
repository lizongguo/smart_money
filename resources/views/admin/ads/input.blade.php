@extends('layouts.admin')
@section('content')
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">广告名称<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="text" name="data[name]" value="{{$data->name}}" lay-verify="required" placeholder="请输入权限名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">广告类型</label>
        <div class="layui-input-inline" style="width: 70%">
            <select lay-verify="required"  name="data[type_id]">
                <option value="">请选择</option>
                @foreach($ad_type as $type_id => $type)
                <option value="{{$type_id}}" @if($type_id = $data->type_id)) selected @endif>{{$type}}</option>
                @endforeach
          </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图片<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input name="data[thumb]" lay-verify="required" id="LAY_image_src" placeholder="图片地址" value="@if($data->thumb){{ $data->thumb}}@endif" class="layui-input">
        </div>
        <div class="layui-input-block layui-btn-container" style="width: auto;">
            <button type="button" class="layui-btn layui-btn-primary uploadImage" id="LAY_image_upload" data-prefix="LAY_image" data-obj="ad">
                <i class="layui-icon">&#xe67c;</i>上传图片
            </button>
            <button class="layui-btn layui-btn-primary" id="LAY_image_show">查看图片</button >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">跳转地址</label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="text" name="data[url]" value="{{$data->url}}" class="layui-input" placeholder="跳转地址" autocomplete="off">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">广告描述<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="255" name="data[content]" style="height: 100px;" placeholder="请输入广告描述" autocomplete="off" class="layui-textarea">{{$data->content}}</textarea>
        </div>
    </div>
    <div class="layui-form-item layui-input-inline">
        <label class="layui-form-label">公开状态</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="data[state]" value="1" lay-skin="switch" lay-text="公开|非公开" @if($data->state == 1) checked @endif>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">顺序号<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="number" name="data[order]" value="{{$data->order ?: 99}}" lay-verify="required" class="layui-input" placeholder="顺序号" autocomplete="off">
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
    }).use(['index', 'form', 'formSelects', 'upload', 'admin'], function () {
        var $ = layui.$,
        upload = layui.upload,
        admin = layui.admin,
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