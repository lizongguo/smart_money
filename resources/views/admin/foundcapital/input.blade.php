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
        <input type="hidden" name="data[fund_id]" value="{{$found_no}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">类型<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select name="data[type]" lay-verify="required" lay-filter="test" id="seltype"> 
                <option value="">请选择类型</option>
                @foreach(config("code.found_type") as $k => $v)
                <option value="{{$k}}" @if($data->type == $k) selected @endif >{{$v}}</option>
                @endforeach
            </select> 
        </div>
    </div>
    <div class="layui-form-item" style="display: none;" id="people">
        <label class="layui-form-label">出资人<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select name="data[user_id]">
                <option value="">请选择</option>
                @foreach($users as $uk => $uv)
                <option value="{{$uv['user_id']}}" @if($data->user_id == $uv->user_id) selected @endif >{{$uv->name}}</option>
                @endforeach
            </select> 
        </div>
    </div>
    <div class="layui-form-item" style="display: none;" id="company">
        <label class="layui-form-label">公司名称<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[name]" value="{{$data->name}}" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">认缴金额(人民币)<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[amount_cn]" value="{{$data->amount_cn}}" lay-verify="required" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">认缴金额(美元)<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input type="text" name="data[amount_us]" value="{{$data->amount_us}}" lay-verify="required" autocomplete="off" class="layui-input" >
        </div>
    </div>
    <div class="layui-inline">
        <label class="layui-form-label">缴纳时间<font style="color: red">*</font></label>
        <div class="layui-input-block">
            <input type="text" name="data[paid_date]" value="{{ $data->paid_date }}" lay-verify="required" id="start" readonly="" autocomplete="off"
                   class="layui-input">
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
    ,version:20200512
}).extend({
index: 'lib/index', //主入口模块
formSelects: 'formSelects-v4.min' //主入口模块
}).use(['index', 'form', 'formSelects', 'upload','laydate'], function () {
            var $ = layui.$
                , form = layui.form
                , admin = layui.admin
                , laydate = layui.laydate
                , formSelects = layui.formSelects
                , table = layui.table;
            laydate.render({
                elem: '#start' //指定元素
                , type: 'date'
                , trigger: 'click'
            });
            form.on('select(test)', function(data){
                if (data.value==1) {
                    $("#people").show();
                    $("#company").hide();
                } else if (data.value==2) {
                    $("#people").hide();
                    $("#company").show();
                } else {
                    $("#people").hide();
                    $("#company").hide();
                }
            });

            var type = {{ $type }};
            if (type==1) {
                $("#people").show();
                $("#company").hide();
            } else if (type==2) {
                $("#people").hide();
                $("#company").show();
            }
});
</script>
@endsection