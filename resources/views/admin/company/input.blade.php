@extends('layouts.admin')
@section('content')
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
        <input type="hidden" name="data[role]" value="1" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">企業名<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" name="data[company_name]" maxlength="100" value="{{$data->company_name}}" lay-verify="required|max:20" placeholder="企業名" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">タイプ<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select name="data[type]" lay-verify="required">
                <option value="">選択してください</option>
                @foreach($jobInfo['company_type'] as $k => $v)
                    @if($k > 0)
                        <option value="{{$k}}" @if($data->type == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">毎日スカウト数<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="number" lay-verify="required|number|sort" name="data[resume_count]" value="{{$data->resume_count}}" placeholder="毎日スカウト数" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">Email<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="email" maxlength="200" name="data[email]" value="{{$data->email}}" lay-verify="required|email" placeholder="Email" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码@if(!isset($data->id))<font style="color: red">*</font>@endif</label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="password" name="data[password]" value="" @if(!isset($data->id)) lay-verify="required|pass" @else lay-verify="pass" @endif placeholder="请输入密码" autocomplete="off" class="layui-input">
            @if(isset($data->id))
                <div class="layui-form-mid layui-word-aux">パスワードを入力しないと変更しないということです。</div>
            @endif
        </div>
    </div>

    @if($data->id)
        <div class="layui-form-item">
            <label class="layui-form-label">会社ホームページ</label>
            <div class="layui-input-inline" style="width: 70%">
                <input type="text" maxlength="200" name="data[company_url]" value="{{$data->company_url}}" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">会社住所</label>
            <div class="layui-input-inline" style="width: 70%">
                <select name="data[address_id]">
                    <option value="0">選択してください</option>
                    @foreach(config("code.resume.country_city") as $k => $v)
                        @if($k > 0)
                            <option value="{{$k}}" @if($data->address_id == $k) selected @endif>{{$v}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-inline" style="width: 70%">
                <input type="text" maxlength="200" name="data[address]" value="{{$data->address}}"  autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">会社設立年月</label>
            <div class="layui-input-inline" style="width: 70%">
                <input type="text" maxlength="200" name="data[found_date]" value="{{$data->found_date}}" autocomplete="off" class="layui-input" >
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">資本金</label>
            <div class="layui-input-inline" style="width: 70%">
                <input type="text" maxlength="200" name="data[capital]" value="{{$data->capital}}" autocomplete="off" class="layui-input" >
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">社員数</label>
            <div class="layui-input-inline" style="width: 70%">
                <select name="data[member_total]" >
                    <option value="0">選択してください</option>
                    @foreach(config("code.company.member_total") as $k => $v)
                        @if($k > 0)
                            <option value="{{$k}}" @if($data->member_total == $k) selected @endif>{{$v}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">業種</label>
            <div class="layui-input-inline">
                <select lay-filter="jobCategorySelect" name="data[fileds]">
                    <option value="">選択してください</option>
                    @foreach(config("code.resume.desired_fileds") as $k => $v)
                        @if($k > 0)
                            <option value="{{$k}}" @if($data->fileds == $k) selected @endif>{{$v}}</option>
                        @endif
                    @endforeach
                    <option value="99" @if($data->fileds == 99) selected @endif>その他</option>
                </select>
            </div>
            <div class="layui-input-inline">
                <input type="text" maxlength="200" class="layui-input" name="data[fileds_other]" value="{{$data->fileds_other}}" placeholder="その他">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">会社概要</label>
            <div class="layui-input-inline" style="width: 70%">
                <textarea type="text" maxlength="2000" name="data[company_summary]" style="height: 100px;" autocomplete="off" class="layui-textarea">{{$data->company_summary}}</textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">事業内容</label>
            <div class="layui-input-inline" style="width: 70%">
                <textarea type="text" maxlength="2000" name="data[company_bussiness]" style="height: 100px;" autocomplete="off" class="layui-textarea">{{$data->company_bussiness}}</textarea>
            </div>
        </div>

    @endif
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

        jobCategorySelect("{{ $data->fileds }}");

        form.on('select(jobCategorySelect)', function(data){
            var val = data.value;
            jobCategorySelect(val);
        });
        function jobCategorySelect(val) {
            if (val == 99) {
                $("input[name='data[fileds_other]']").show();
                $("input[name='data[fileds_other]']").attr('lay-verify', 'required');
            } else {
                $("input[name='data[fileds_other]']").attr('lay-verify', '');
                $("input[name='data[fileds_other]']").hide();
            }
        }

        form.verify({
            sort: function(value,item){
                if (value > 99999) {
                    return '毎日スカウト数最大99999。';
                }
            }
        });
        
    });
</script>
@endsection