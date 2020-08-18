@extends('layouts.admin')
@section('content')
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所在地（国・地域）<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select  lay-verify="required" lay-filter="jobCategorySelect" name="data[nationality_id]">
                <option value="">選択してください</option>
                @foreach(config("code.resume.nationality_agent") as $k => $v)
                    @if($k > 0)
                        <option value="{{$k}}" @if($data->nationality_id == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="data[nationality]" value="{{$data->nationality}}" placeholder="その他">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">会社名<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" name="data[agent_name]" value="{{$data->agent_name}}" lay-verify="required|max:20" placeholder="会社名" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">提携希望<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            @php
                $target = explode(",", $data->type);
            @endphp
            @foreach(config("code.agent.type") as $k => $v)
                @if ($k > 0)
                    <input type="checkbox" lay-verify="otherReq"  name="type" title="{{$v}}" @if(in_array($k, $target)) checked @endif value="{{$k}}">
                @endif
            @endforeach
            <input type="hidden" name="data[type]">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">Email<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="email" name="data[email]" value="{{$data->email}}" lay-verify="required|email" placeholder="Email" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码@if(!isset($data->id))<font style="color: red">*</font>@endif</label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="text" name="data[password]" value="" @if(!isset($data->id)) lay-verify="required|pass" @else lay-verify="pass" @endif placeholder="请输入密码" autocomplete="off" class="layui-input">
            @if(isset($data->id))
                <div class="layui-form-mid layui-word-aux">パスワードを入力しないと変更しないということです。</div>
            @endif
        </div>
    </div>

        <div class="layui-form-item">
            <label class="layui-form-label">会社住所<font style="color: red">*</font></label>
            <div class="layui-input-inline" style="width: 70%">
                <input type="text" name="data[agent_address]" value="{{$data->agent_address}}" autocomplete="off" class="layui-input" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">会社ホームページ</label>
            <div class="layui-input-inline" style="width: 70%">
                <input type="text" name="data[url]" value="{{$data->url}}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">担当者名<font style="color: red">*</font></label>
            <div class="layui-input-inline" style="width: 70%">
                <input type="text" name="data[principal_name]" lay-verify="required" value="{{$data->principal_name}}" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">電話番号<font style="color: red">*</font></label>
            <div class="layui-input-inline" style="width: 70%">
                <input type="text" name="data[cell_phone]" lay-verify="required|tel" value="{{$data->cell_phone}}" autocomplete="off" class="layui-input">
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

        jobCategorySelect("{{ $data->nationality_id }}");

        form.on('select(jobCategorySelect)', function(data){
            var val = data.value;
            jobCategorySelect(val);
        });
        function jobCategorySelect(val) {
            if (val == 18) {
                $("input[name='data[nationality]']").show();
                $("input[name='data[nationality]']").attr('lay-verify', 'required');
            } else {
                $("input[name='data[nationality]']").attr('lay-verify', '');
                $("input[name='data[nationality]']").hide();
            }
        }

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
            }
        });
        
    });
</script>
@endsection