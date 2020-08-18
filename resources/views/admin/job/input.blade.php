@extends('layouts.admin')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/formSelects-v4.css')}}"/>
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[job_id]" value="{{$data->job_id}}" autocomplete="off" class="layui-input">
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">求人管理ID</label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" readonly disabled maxlength="200" name="data[account_code]" value="{{$data->account_code ? $data->account_code : $data->job_id}}" placeholder="求人管理ID" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">企業</label>
        <div class="layui-input-inline">
            <select name="data[company_id]">
                <option value="">選択してください</option>
                @foreach($company as $v)
                    <option value="{{$v['id']}}" @if($data->company_id == $v['id']) selected @endif>{{$v['company_name']}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">システム管理ID</label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" maxlength="200" name="data[job_code]" value="{{$data->job_code}}" placeholder="求人管理ID" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">ポジション</label>
        <div class="layui-input-inline" style="width: 70%" >
            <textarea type="text" maxlength="255" name="data[position]" style="height: 100px;" placeholder="ポジション" autocomplete="off" class="layui-textarea">{{$data->position}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">掲載期間<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" autocomplete="off" name="data[job_period_start]" value="{{$data->job_period_start}}"  id="test-laydate-start" placeholder="開始日" lay-verify="required">
        </div>
        <div class="layui-form-mid">
            〜
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" autocomplete="off" name="data[job_period_end]" value="{{$data->job_period_end}}" id="test-laydate-end" placeholder="終了日" lay-verify="required">
        </div>
        @foreach($jobInfo['job_period_type'] as $k => $v)
            <input type="radio" name="data[job_period_type]" lay-filter="job_period_typeSelect" value="{{$k}}" @if($data->job_period_type == $k || (!$data->job_period_type && $k == 1)) checked @endif title="{{$v}}">
        @endforeach
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">募集職種名<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" maxlength="200" name="data[job_name]" value="{{$data->job_name}}" lay-verify="required" placeholder="募集職種名" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">職種カテゴリ</label>
        <div class="layui-input-inline">
            <select lay-filter="jobCategorySelect" name="data[job_category]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['desired_job_type'] as $k => $v)
                    @if($k > 0)
                    <option value="{{$k}}" @if($data->job_category == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
                <option value="99" @if($data->job_category == 99) selected @endif>その他</option>
            </select>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="data[job_other]" value="{{$data->job_other}}" placeholder="その他">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">仕事内容<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[jp_detail]" style="height: 100px;" placeholder="仕事内容" autocomplete="off" lay-verify="required" class="layui-textarea">{{$data->jp_detail}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label" style="font-size: 18px; font-weight:bold;">応募資格</label>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">日本語レベル<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select  lay-verify="required" name="data[jp_level_2]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['jp_level_2'] as $k => $v)
                    @if($k > 0)
                        <option value="{{$k}}" @if($data->jp_level_2 == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="layui-input-inline">
            <select  lay-verify="required" name="data[jp_level]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['jp_level'] as $k => $v)
                    @if($k > 0)
                        <option value="{{$k}}" @if($data->jp_level == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">英語レベル</label>
        <div class="layui-input-inline">
            <select name="data[en_level]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['en_level'] as $k => $v)
                    @if($k > 0)
                        <option value="{{$k}}" @if($data->en_level == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">その他</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[en_level_other]" style="height: 100px;" placeholder="仕事内容" autocomplete="off" class="layui-textarea">{{$data->en_level_other}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">募集対象</label>
        <div class="layui-input-inline" style="width: 70%">
            @php
                $target = explode(",", $data->target);
            @endphp
            @foreach($resumeInfo['target'] as $k => $v)
                @if ($k > 0)
                    <input type="checkbox" lay-verify="otherReq"  name="target" title="{{$v}}" @if(in_array($k, $target)) checked @endif value="{{$k}}">
                @endif
            @endforeach
            <input type="hidden" name="data[target]">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        {{--<div class="layui-input-inline">--}}
            {{--<select  lay-verify="required" lay-filter="nationalitySelect" name="data[nationality]">--}}
                {{--<option value="">選択してください</option>--}}
                {{--@foreach($resumeInfo['nationality'] as $k => $v)--}}
                    {{--@if($k > 0)--}}
                        {{--<option value="{{$k}}" @if($data->nationality == $k) selected @endif>{{$v}}</option>--}}
                    {{--@endif--}}
                {{--@endforeach--}}
            {{--</select>--}}
        {{--</div>--}}
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="data[nationality_other]" value="{{$data->nationality_other}}" placeholder="国・地域">
        </div>
        <div class="layui-input-inline">
            籍大歓迎
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">対象年齢</label>
        <div class="layui-input-inline">
            <select name="data[age_start]">
                <option value="">選択してください</option>
                @for($i = 18; $i <= 50; $i++)
                    @if($k > 0)
                        <option value="{{$i}}" @if($data->age_start == $i) selected @endif>{{$i}}歳</option>
                    @endif
                @endfor
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="data[age_end]">
                <option value="">選択してください</option>
                @for($i = 18; $i <= 50; $i++)
                    @if($k > 0)
                        <option value="{{$i}}" @if($data->age_end == $i) selected @endif>{{$i}}歳</option>
                    @endif
                @endfor
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">年齢制限理由</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[age_other]" style="height: 100px;" placeholder="年齢制限理由" autocomplete="off" class="layui-textarea">{{$data->age_other}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">海外在住者の採用</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="data[employment_overseas]" title="有り" @if($data->employment_overseas == 1) checked @endif value="1">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">就労ビザのサポート</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="data[working_visa]" title="有り" @if($data->working_visa == 1) checked @endif value="1">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">雇用形態<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            @php
                $working_form = explode(",", $data->working_form);
            @endphp
            @foreach($resumeInfo['working_form'] as $k => $v)
                @if ($k > 0)
                    <input type="checkbox" lay-verify="otherReq" name="working_form" title="{{$v}}" @if(in_array($k, $working_form)) checked @endif value="{{$k}}">
                @endif
            @endforeach
            <input type="hidden" name="data[working_form]">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label" style="font-size: 18px; font-weight:bold;">給与</label>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">年俸</label>
        <div class="layui-input-inline">
            <select name="data[yearly_income_low]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['wage_arr_1'] as $k => $v)
                    @if($k > 0 && $v != "上限なし")
                        <option value="{{$k}}" @if($data->yearly_income_low == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="data[yearly_income_up]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['wage_arr_1'] as $k => $v)
                    @if($k > 0)
                        <option value="{{$k}}" @if($data->yearly_income_up == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">月給</label>
        <div class="layui-input-inline">
            <select name="data[monthly_income_low]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['wage_arr_2'] as $k => $v)
                    @if($k > 0 && $v != "上限なし")
                        <option value="{{$k}}" @if($data->monthly_income_low == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="data[monthly_income_up]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['wage_arr_2'] as $k => $v)
                    @if($k > 0)
                        <option value="{{$k}}" @if($data->monthly_income_up == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">時給</label>
        <div class="layui-input-inline">
            <select name="data[hourly_from]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['wage_arr_3'] as $k => $v)
                    @if($k > 0 && $v != "上限なし")
                        <option value="{{$k}}" @if($data->hourly_from == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="data[hourly_to]">
                <option value="">選択してください</option>
                @foreach($resumeInfo['wage_arr_3'] as $k => $v)
                    @if($k > 0)
                        <option value="{{$k}}" @if($data->hourly_to == $k) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">その他</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[yearly_income_memo]" style="height: 100px;" placeholder="その他" autocomplete="off" class="layui-textarea">{{$data->yearly_income_memo}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label" style="font-size: 18px; font-weight:bold;">勤務地</label>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">都道府県<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <select xm-select="prefectureSelects" lay-verify="required" lay-filter="prefectureSelect" name="data[prefecture]">
                <option value="">選択してください</option>
                @php
                    $prefecture = explode(",", $data->prefecture );
                @endphp
                @foreach($resumeInfo['country_city'] as $k => $v)
                    @if($k > 0)
                        <option value="{{$k}}" @if(in_array($k, $prefecture)) selected @endif>{{$v}}</option>
                    @endif
                @endforeach
                <option value="99" @if(in_array(99, $prefecture)) selected @endif>その他</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item prefecture_other">
        <label class="layui-form-label"></label>

        <div class="layui-input-inline">
            <input type="text" class="layui-input" maxlength="200" name="data[prefecture_other]" value="{{$data->prefecture_other}}" placeholder="その他">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">住所</label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" name="data[working_place]" maxlength="200" value="{{$data->working_place}}" placeholder="住所" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">その他</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[working_place_other]" style="height: 100px;" placeholder="その他" autocomplete="off" class="layui-textarea">{{$data->working_place_other}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">勤務時間</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[working_time_all]" style="height: 100px;" placeholder="勤務時間" autocomplete="off" class="layui-textarea">{{$data->working_time_all}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">休日・休暇</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[working_time_holiday]" style="height: 100px;" placeholder="休日・休暇" autocomplete="off" class="layui-textarea">{{$data->working_time_holiday}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">福利厚生</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[welfare]" style="height: 100px;" placeholder="福利厚生" autocomplete="off" class="layui-textarea">{{$data->welfare}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">選考プロセス</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[interview_process]" style="height: 100px;" placeholder="選考プロセス" autocomplete="off" class="layui-textarea">{{$data->interview_process}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">その他</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" maxlength="2000" name="data[others]" style="height: 100px;" placeholder="その他" autocomplete="off"  class="layui-textarea">{{$data->others}}</textarea>
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

        //ready
        jobCategorySelect("{{ $data->job_category }}");
        //var nationality = $("select[name='data[nationality]']").find("option:selected").text();
        //nationalitySelect(nationality);
        @if(in_array(99, $prefecture))
            prefectureSelect(99);
        @else
            prefectureSelect(0);
        @endif

        job_period_typeSelect("{{  $data->job_period_type }}");


        //开始日期
        var insStart = laydate.render({
            elem: '#test-laydate-start'
            ,done: function(value, date){
                //更新结束日期的最小日期
                insEnd.config.min = lay.extend({}, date, {
                    month: date.month - 1
                });

                //自动弹出结束日期的选择器
                insEnd.config.elem[0].focus();
            }
        });

        //结束日期
        var insEnd = laydate.render({
            elem: '#test-laydate-end'
            ,done: function(value, date){
                //更新开始日期的最大日期
                insStart.config.max = lay.extend({}, date, {
                    month: date.month - 1
                });
            }
        });

        form.on('select(jobCategorySelect)', function(data){
            var val = data.value;
            jobCategorySelect(val);
        });
        function jobCategorySelect(val) {
            if (val == 99) {
                $("input[name='data[job_other]']").show();
                $("input[name='data[job_other]']").attr('lay-verify', 'required');
            } else {
                $("input[name='data[job_other]']").attr('lay-verify', '');
                $("input[name='data[job_other]']").hide();
            }
        }

        // form.on('select(nationalitySelect)', function(data){
        //     var val = data.elem[data.elem.selectedIndex].text;
        //     nationalitySelect(val);
        // });


        function nationalitySelect(val) {
            if (val == "その他") {
                $("input[name='data[nationality_other]']").show();
                $("input[name='data[nationality_other]']").attr('lay-verify', 'required');
            } else {
                $("input[name='data[nationality_other]']").attr('lay-verify', '');
                $("input[name='data[nationality_other]']").hide();
            }
        }

        form.on('radio(job_period_typeSelect)', function(data){
            var val = data.value;
            job_period_typeSelect(val);
        });
        function job_period_typeSelect(val) {
            if (val != 1) {
                $("input[name='data[job_period_start]']").attr('lay-verify', '');
                $("input[name='data[job_period_end]']").attr('lay-verify', '');
            } else {
                $("input[name='data[job_period_start]']").attr('lay-verify', 'required');
                $("input[name='data[job_period_end]']").attr('lay-verify', 'required');
            }
        }

        formSelects.on('prefectureSelects', function(id, vals, val, isAdd, isDisabled){
            if (val.value == 99) {
                if (isAdd === true) {
                    prefectureSelect(99);
                } else {
                    prefectureSelect(0);
                }
            }
        });

        function prefectureSelect(val) {
            if (val == 99) {
                $(".prefecture_other").show();
                $("input[name='data[prefecture_other]']").show();
                $("input[name='data[prefecture_other]']").attr('lay-verify', 'required');
            } else {
                $("input[name='data[prefecture_other]']").attr('lay-verify', '');
                $("input[name='data[prefecture_other]']").hide();
                $(".prefecture_other").hide();
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
                if((!isTrue || !value) && verifyName != 'target'){
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
    })
</script>
@endsection