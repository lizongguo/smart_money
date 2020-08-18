@extends('layouts.admin')
@section('content')
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">父级菜单<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select name="data[parent_id]" >
                @foreach($parents as $pid => $parent)
                <option value="{{$pid}}" @if($data->parent_id == $pid) selected @endif>{!! $parent !!}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分类名称<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="text" name="data[name]" value="{{$data->name}}" lay-verify="required" placeholder="请输入分类名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">公开状态</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="data[state]" value="1" lay-skin="switch" lay-text="公开|非公开" @if($data->state == 1) checked @endif>
        </div>
    </div>
    
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="LAY-input-front-submit" id="LAY-input-front-submit" value="确认">
    </div>
</div>

<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<script>
    function LA() {}
    LA._token = "{{csrf_token()}}";
    layui.config({
        uriHost: '{{asset("/admin")}}/', //项目管理端path
        base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'form'], function () {
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