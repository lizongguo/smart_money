@extends('layouts.admincontent')
@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">网站设置</div>
                <div class="layui-card-body" pad15>
                    <div class="layui-form" wid100 lay-filter="">
                        <div class="layui-form-item">
                            <label class="layui-form-label">网站名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="sitename" lay-verify="required" placeholder="请输入网站名称" value="{{config('site.sitename')}}" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">网站域名</label>
                            <div class="layui-input-block">
                                <input type="text" name="domain" lay-verify="url" placeholder="请输入网站域名" value="{{config('site.domain', asset('/'))}}" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">前台标题</label>
                            <div class="layui-input-block">
                                <textarea name="title" lay-verify="required" placeholder="请输入前台标题" class="layui-textarea">{{config('site.title')}}</textarea>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">META关键词</label>
                            <div class="layui-input-block">
                                <textarea name="keywords" class="layui-textarea" placeholder="多个关键词用英文状态 , 号分割">{{config('site.keywords')}}</textarea>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">META描述</label>
                            <div class="layui-input-block">
                                <textarea name="description" class="layui-textarea" placeholder="META描述，方便搜索引擎收录">{{config('site.description')}}</textarea>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">版权信息</label>
                            <div class="layui-input-block">
                                <textarea name="copyright"  class="layui-textarea">{{config('site.copyright', '© '.date('Y').' www.kbftech.com MIT license')}}</textarea>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="set_website">确认保存</button>
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
    });
</script>
@endsection