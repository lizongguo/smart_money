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
        <input type="hidden" name="data[fund_id]" value="{{$project_no}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">名称<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[name]" value="{{$data->name}}" lay-verify="required" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">类型</label>
        <div class="layui-input-block">
          @foreach(config("code.content_type") as $k => $v)
            <input  lay-filter="test" type="radio" name="data[content_type]" value="{{ $k }}" title="{{ $v }}" @if($content_type==$k) checked @endif>
          @endforeach
        </div>
    </div>
    <div class="layui-form-item" style="display: block;" id="file_area">
        <label class="layui-form-label">文件<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input name="data[path]" id="LAY_file_src" placeholder="文件地址" value="@if($data->path){{ $data->path}}@endif" class="layui-input">
        </div>
        <div class="layui-input-block layui-btn-container" style="width: auto;">
            <button type="button" class="layui-btn layui-btn-primary uploadFile" id="LAY_file_upload" data-prefix="LAY_file" data-obj="adminFile">
                <i class="layui-icon">&#xe67c;</i>上传文件
            </button>
        </div>
    </div>
    <div class="layui-form-item" style="display: none;" id="content_area">
        <label class="layui-form-label">文本<font style="color: red">*</font></label>
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
        
        form.on('radio(test)', function(data){
            if(data.value==1) {
                $("#content_area").hide();
                $("#file_area").show();
            } else {
                $("#content_area").show();
                $("#file_area").hide();
            }
        });
        var content_type = {{ $content_type }};
        if (content_type==2) {
            $("#content_area").show();
            $("#file_area").hide();
        }

})
</script>
@endsection