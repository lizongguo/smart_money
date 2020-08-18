@extends('layouts.admin')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/formSelects-v4.css')}}"/>
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">タイトル<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <textarea type="text" maxlength="200" name="data[title]" style="height: 100px;" placeholder="ポジション" autocomplete="off" class="layui-textarea" lay-verify="required">{{$data->title}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">URl<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" name="data[url]" maxlength="200" value="{{$data->url}}"  lay-verify="required|url" placeholder="URl" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">ソート<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="number" name="data[sort]" value="{{$data->sort}}"  lay-verify="required|sort" placeholder="ソート" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">状態<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="checkbox" name="data[status]" value="1" lay-skin="switch" lay-text="公開する|無効化" @if(!empty($data->status) && $data->status == 1) checked @endif>
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
    }).use(['laydate','layedit', 'index', 'form', 'upload', 'edit', 'formSelects'], function () {
        var $ = layui.$,
        admin = layui.admin,
        layedit = layui.layedit,
        upload = layui.upload,
        formSelects = layui.formSelects,
        form = layui.form;
        laydate = layui.laydate;

        form.verify({
            otherReq: function(value,item){
                var $ = layui.$;
                var verifyName=$(item).attr('name')
                    , verifyType=$(item).attr('type')
                    ,formElem=$(item).parents('.layui-form')//获取当前所在的form元素，如果存在的话
                    ,verifyElem=formElem.find("input[name='"+verifyName+"']")//获取需要校验的元素
                    ,isTrue= verifyElem.is(':checked')//是否命中校验
                    ,focusElem = verifyElem.next().find('i.layui-icon');//焦点元素
                if(!isTrue || !value){
                    //定位焦点
                    focusElem.css(verifyType=='radio'?{"color":"#FF5722"}:{"border-color":"#FF5722"});
                    //对非输入框设置焦点
                    focusElem.first().attr("tabIndex","1").css("outline","0").blur(function() {
                        focusElem.css(verifyType=='radio'?{"color":""}:{"border-color":""});
                    }).focus();
                    return '必須項目は空ではだめです。';
                } else {
                    var tem = [];
                    $("input[name='"+verifyName+"']:checked").each(function(){
                        tem.push($(this).val());
                    });
                    $("input[name='data["+verifyName+"]']").val( tem.join(",") );
                }
            },
            sort: function(value,item){
                if (value > 99999) {
                    return 'ソート最大99999。';
                }
            }
        });
    })
</script>
@endsection