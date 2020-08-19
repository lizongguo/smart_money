@extends('layouts.admincontent')
@section('content')
<style>
    .layui-form-label{
        width: 200px;
    }
    .layui-input-block{
        margin-left:230px;
        width: 70%;
    }
</style>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <!--<div class="layui-card-header">网站设置</div>-->
                <div class="layui-card-body" pad15>
                    <div class="layui-tab">
                        <ul class="layui-tab-title">
                            <li class="layui-this">基本信息</li>
                            <li>项目信息</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                <div class="layui-card-header">基本信息</div>
                                <div class="layui-card-body">
                                    <table class="layui-table table-striped">
                                      <tbody>
                                          <tr>
                                              <td  width="200">基金现用名</td>
                                              <td>{{ $data->current_name }}</td>
                                          </tr>
                                          <tr>
                                              <td  width="200">基金曾用名</td>
                                              <td>{{ $data->ever_name }}</td>
                                          </tr>
                                          <tr>
                                              <td  width="200">现任执行事务合伙人（GP)</td>
                                              <td>{{ $data->current_gp }}</td>
                                          </tr>
                                      </tbody>
                                  </table>
                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="layui-card-header">项目信息</div>
                                <div class="layui-card-body">
                                    <table class="layui-table">
                                      <colgroup>
                                        <col width="200">
                                        <col width="150">
                                        <col width="100">
                                        <col width="100">
                                      </colgroup>
                                      <thead>
                                        <tr>
                                          <th>公司名称</th>
                                          <th>公司地址</th>
                                          <th>官网</th>
                                          <th>公司状态</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                          @foreach($infos as $info)
                                        <tr>
                                          <td>{{$info->company_name}}</td>
                                          <td>{{$info->address}}</td>
                                          <td>{{$info->website}}</td>
                                          <td>{{$info->state}}</td>
                                        </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
}).use(['index', 'form', 'upload', 'set'], function () {
    var $ = layui.$
            , setter = layui.setter
            , admin = layui.admin
            , form = layui.form
            , upload = layui.upload;
    //网站设置
    form.on('submit(set_website)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        admin.req({
            url: '{{route("setting.site")}}'
            , data: {'_token': LA._token, data: obj.field}
            , type: 'post'
            , success: function (res) {

            }
            , done: function (res) {
                //登入成功的提示与跳转
                layer.msg('内容保存成功', {
                    offset: '15px'
                    , icon: 1
                    , time: 1000
                }, function () {
                    location.reload();
                });
            }
        });
    });
    
    var saveUri = '{{route("setting.save", ["category" => "_cate_"])}}';
    
    function saveData(field, cate) {
        admin.req({
            url: saveUri.replace('_cate_', cate)
            , data: field
            , type: 'post'
            , success: function (res) {

            }
            , done: function (res) {
                //登入成功的提示与跳转
                layer.msg('内容保存成功', {
                    offset: '15px'
                    , icon: 1
                    , time: 1000
                }, function () {
                    location.reload();
                });
            }
        });
    }
    
    //微信小程序设置
    form.on('submit(set_wxapp)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'wxapp';
        saveData(obj.field, cate);
    });
    form.on('switch(wxapp)', function(data){
        if (data.elem.checked) {
            $('#wxapp_seting').show();
            $('#wxapp_seting input').attr('lay-verify', 'required');
        } else {
            $('#wxapp_seting').hide();
            $('#wxapp_seting input').removeAttr('lay-verify');
        }
        form.render();
    });
    
    //支付宝小程序设置
    form.on('submit(set_aliapp)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'aliapp';
        saveData(obj.field, cate);
    });
    form.on('switch(aliapp)', function(data){
        if (data.elem.checked) {
            $('#aliapp_seting').show();
            $('#aliapp_seting input,#aliapp_seting textarea').attr('lay-verify', 'required');
        } else {
            $('#aliapp_seting').hide();
            $('#aliapp_seting input,#aliapp_seting textarea').removeAttr('lay-verify');
        }
        form.render();
    });
    
    //阿里大于短信设置
    form.on('submit(set_sms)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'sms';
        saveData(obj.field, cate);
    });
    //阿里推送设置
    form.on('submit(set_push)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'push';
        saveData(obj.field, cate);
    });
    form.on('switch(push)', function(data){
        if (data.elem.checked) {
            $('#push_seting').show();
            $('#push_seting input,#aliapp_seting textarea').attr('lay-verify', 'required');
        } else {
            $('#push_seting').hide();
            $('#push_seting input,#aliapp_seting textarea').removeAttr('lay-verify');
        }
        form.render();
    });
    
    //阿里推送设置
    form.on('submit(set_takeout)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'takeout';
        saveData(obj.field, cate);
    });
    form.on('switch(takeout)', function(data){
        if (data.elem.checked) {
            $('#takeout_seting').show();
            $('#takeout_seting input,#aliapp_seting select').attr('lay-verify', 'required');
        } else {
            $('#takeout_seting').hide();
            $('#takeout_seting input,#aliapp_seting select').removeAttr('lay-verify');
        }
        form.render();
    });
    
    
    
    
});
</script>
@endsection