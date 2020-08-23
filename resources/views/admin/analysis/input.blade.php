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
        <label class="layui-form-label" style="width: 120px;">基金<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 500px;">
            <select xm-select="fund_id" name="data[fund_id]" xm-select-search xm-select-radio xm-select-direction="down" xm-select-height="150px" xm-select-show-count="5" lay-verify="required">
                <option value="">请选择基金</option>
                @foreach($funds as $fund)
                <option value="{{$fund->id}}" @if($data->fund_id == $fund->id) selected @endif >{{$fund->name}}</option>
                @endforeach
            </select> 
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 120px;">股票<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 500px;">
            <select xm-select="stock_id" name="data[stock_id]" xm-select-search xm-select-radio xm-select-direction="down" xm-select-height="150px" xm-select-show-count="5" lay-verify="required">
                <option value="">请选择股票</option>
                @foreach($stocks as $stock)
                <option value="{{$stock->id}}" @if($data->stock_id == $stock->id) selected @endif >{{$stock->name}}</option>
                @endforeach
            </select> 
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 120px;">持股市值(万元)</label>
        <div class="layui-input-inline" style="width: 500px;">
            <input type="text" name="data[amount]" maxlength="10" value="{{$data->amount}}" autocomplete="off" class="layui-input" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 120px;">持股总数(万股)</label>
        <div class="layui-input-inline" style="width: 500px;">
            <input type="text" name="data[stock_num]" maxlength="10" value="{{$data->stock_num}}" autocomplete="off" class="layui-input" lay-verify="number">
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