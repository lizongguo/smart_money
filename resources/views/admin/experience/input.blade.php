@extends('layouts.admin')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/formSelects-v4.css')}}"/>
    <div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
        {{csrf_field()}}
        <div class="layui-form-item layui-hide">
            <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">氏名</label>
            <div class="layui-input-inline" style="width: 70%" >
                <label class="layui-form-label"  style="width: 90%;text-align: left">{{$data->name}}</label>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">ふりがな</label>
            <div class="layui-input-inline" style="width: 70%" >
                <label class="layui-form-label" style="width: 90%;text-align: left">{{$data->name_kana}}</label>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">推薦文</label>
            <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" name="data[recommendation]"
                      placeholder="推薦文は空にできません" lay-verify="required|recommendationMax" autocomplete="off" class="layui-textarea">{{$data->recommendation}}</textarea>
            </div>
        </div>

        <div class="layui-form-item layui-hide">
            <input type="button" lay-submit lay-filter="LAY-input-front-submit" id="LAY-input-front-submit" value="確認">
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
        }).use(['layedit', 'index', 'form'], function () {
            var $ = layui.$,
                admin = layui.admin,
                layedit = layui.layedit,
                form = layui.form;
                form.verify({
                    recommendationMax: function (val) {
                        console.log(val);
                        if (!/^(.){1,1000}$/iu.test(val)) {
                            return '推薦文は1000文字を超えることはできません。';
                        }
                    }
                });

        })
    </script>
@endsection