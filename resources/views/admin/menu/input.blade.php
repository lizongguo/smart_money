@extends('layouts.admin')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/formSelects-v4.css')}}"/>
<link href="{{asset('/layuiadmin/style/fontawesome-iconpicker.min.css')}}" rel="stylesheet" type="text/css"/>
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
        <label class="layui-form-label">标题<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="text" name="data[title]" value="{{$data->title}}" lay-verify="required" placeholder="请输入菜单标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图标<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 220px">
            <label class="layui-btn layui-btn-sm layui-btn-primary input-group-addon"><i class="fa fa-bars"></i></label>
            <input type="text" name="data[icon]" id="icon" lay-verify="required" readonly="" value="{{isset($data->icon) ? $data->icon : 'fa-bars'}}" placeholder="选择图标"  autocomplete="off" class="layui-input" style="margin-left: 0px;width: 150px;display: inline-block">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">路径</label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[uri]" value="{{$data->uri}}" placeholder="输入路径" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-inline" style="width: 50%">
            <select xm-select="roles" xm-select-direction="down" xm-select-height="150px" xm-select-show-count="6"  name="data[roles]" >
                <option value="">选择角色</option>
                @foreach($roles as $role)
                <option value="{{$role->id}}" @if(in_array($role->id, $data->roles)) selected @endif>{{$role->name}}</option>
                @endforeach
          </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">权限</label>
        <div class="layui-input-inline" style="width: 50%">
            <select name="data[permission_id]" >
                <option value="0">请选择权限</option>
                @foreach($permissions as $permission)
                <option value="{{$permission->id}}" @if($data->permission_id == $permission->id) selected @endif>{{$permission->name}}</option>
                @endforeach
            </select>
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
        index: 'lib/index', //主入口模块
        formSelects: 'formSelects-v4.min', //主入口模块
        iconpicker: 'fontawesome-iconpicker.min'
    }).use(['index', 'form', 'formSelects', 'iconpicker'], function () {
        var $ = layui.$,
        form = layui.form;
        formSelects = layui.formSelects;
        $('#icon').iconpicker({placement:'bottomLeft'});
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