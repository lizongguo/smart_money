@extends('layouts.admin')
@section('content')
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所属店铺<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <select lay-verify="required" name="data[shop_id]">
                <option value="">请选择店铺</option>
                @foreach($shops as $shop)
                <option value="{{$shop->id}}" @if($shop->id == $data->shop_id) selected @endif>{{$shop->shop_name}}</option>
                @endforeach
          </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">队列名称<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" name="data[name]" value="{{$data->name}}" lay-verify="required|min:2|max:10" placeholder="队列名称：2-10个字构成" autocomplete="off" class="layui-input">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">队列前缀<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="text" name="data[prefix]" value="{{$data->prefix}}" lay-verify="required" placeholder="例如：(A、B、C)" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="text" name="data[desc]" lay-verify="required|max:50" value="{{$data->desc}}"  placeholder="例如：(2-4人)" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">平均等待时间（分）<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="number" name="data[average_time]" lay-verify="required|min:1|max:50" value="{{$data->average_time}}"  placeholder="例如：60" autocomplete="off" class="layui-input">
        </div>
    </div>
    
    
    
    <div class="layui-form-item">
        <label class="layui-form-label">公开状态</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="data[state]" value="1" lay-skin="switch" lay-text="正常|禁用" @if(!isset($data->state) ||$data->state == 1) checked @endif>
        </div>
    </div>
    
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="LAY-input-front-submit" id="LAY-input-front-submit" value="确认">
    </div>
</div>

<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<script src="{{asset("/layuiadmin/style/Content/ace/ace.js")}}"></script>
    <script>
    layui.config({
        uriHost: '{{asset("/admin")}}/', //项目管理端path
        base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
    }).extend({
        index: 'lib/index', //主入口模块
        formSelects: 'formSelects-v4.min' //主入口模块
    }).use(['layedit', 'index', 'form', 'upload', 'edit'], function () {
        var $ = layui.$,
        admin = layui.admin,
        layedit = layui.layedit,
        upload = layui.upload,
        form = layui.form;
        
    })
</script>
@endsection