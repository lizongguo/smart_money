@extends('layouts.web')
@section('content')
    <div class="main">
        <div class="main_application">
            <div class="application_info">
                <h2>{{ $jobInfo['job_name'] ? $jobInfo['job_name'] : $jobInfo['position'] }}</h2>
            </div>
            <p class="application_tips">「本求人への応募は略歴(個人情報無し)の提出になります。
                <br/> 最新の略歴 は「<a href="{{ route('web.experiences.my') }}">履歴書・職務経歴書</a>」を更新してから反映されます。」</p>
            <form id="testForm">
                <table class="application_info_table">
                    <tr>
                        <td class="info_tit">国籍・地域</td>
                        <td>@if($resumeInfo['nationality'][$data->nationality_id] == "その他") {{ $data->nationality }} @else {{$resumeInfo['nationality'][$data->nationality_id]}} @endif</td>
                        <td class="info_tit">性別</td>
                        <td>{{ $resumeInfo['sex'][$data->sex] }}</td>
                    </tr>
                    <tr>
                        <td class="info_tit">年齢</td>
                        <td>{{ $data->age_str }}</td>
                        <td class="info_tit">現住所</td>
                        <td>@if($data->address){{$resumeInfo['address'][$data->address]}}@endif @if($data->address == 1 && $data->address_id)( {{$resumeInfo['country_city'][$data->address_id]}} )@endif</td>
                    </tr>
                    <tr>
                        <td class="info_tit">日本語レベル</td>
                        <td>@if($data->jp_level){{$resumeInfo['jp_level_2'][$data->jp_level]}}@endif</td>
                        <td class="info_tit">日本滞在年数</td>
                        <td>@if($data->address_extra_2 == 0) 無 @elseif($data->address_extra_2 == -1) 1年未満 @elseif($data->address_extra_2 == 99) 10年以上 @elseif($data->address_extra_2 > 0) {{$data->address_extra_2}}年 @endif</td>
                    </tr>
                    <tr>
                        <td class="info_tit">就業状況</td>
                        <td>@if($data->employment_status){{$resumeInfo['employment_status'][$data->employment_status]}}@endif</td>
                    @if($data->employment_status == 1)
                        <td class="info_tit">卒業見込み</td>
                        <td>{{$data->employment_status_extra}}年</td>
                    @else
                        <td class="info_tit">仕事経験年数</td>
                        <td>@if($data->employment_status_extra == -1) 1年未満 @elseif($data->employment_status_extra == 99) 10年以上 @elseif($data->employment_status_extra > 0) {{$data->employment_status_extra}}年 @endif</td>
                    @endif
                    </tr>
                    <tr>
                        <td class="info_tit">文理区分</td>
                        <td>@if($data->science_arts){{$resumeInfo['science_arts'][$data->science_arts]}} @endif</td>
                        <td class="info_tit">最終学歴</td>
                        <td>@if($data->final_education){{$resumeInfo['final_education'][$data->final_education]}} @endif @if($data->university) ({{ $data->university }}) @endif</td>
                    </tr>
                    <tr>
                        <td class="info_tit">学科専攻</td>
                        <td>@if($data->major){{$data->major}} @endif</td>
                        <td class="info_tit">英語レベル</td>
                        <td>@if($data->en_level){{$resumeInfo['en_level'][$data->en_level]}}@endif</td>
                    </tr>
                    @if($data->it_skill)
                    <tr>
                        <td class="info_tit" valign="top">ITスキル</td>
                        <td colspan="3">@if($data->it_skill)
                                @php
                                    $it_skill = explode("," , $data->it_skill);
                                @endphp
                                @foreach($it_skill as $v)
                                    {{$resumeInfo['it_skill'][$v]}}&nbsp;
                                @endforeach
                            @endif
                            @if($data->it_skill_other){{$data->it_skill_other}} @endif</td>

                    </tr>
                    @endif
                    <tr>
                        <td class="info_tit" valign="top">自己PR<br/>志望動機</td>
                        <td colspan="3"><textarea class="text_area" maxlength="2000" required name="note">{{ $data->pr_other }}</textarea></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <a href="{{ route('web.experiences.uploadVideo', ['upload' => 1]) }}"><span class="go_resume_btn"><i class="fa fa-id-card-o" aria-hidden="true"></i>自己紹介の映像を作成</span></a>
                            <div class="checkbox_box"><input type="checkbox" checked value="1" name="resume_status"> <a href="javascript:void(0)" id="popupTrigger3" data-popup-target="privacyPopup">自己紹介の映像を企業に公開する</a></div>
                            <p class="alert_text">応募する前に、一度「日本就職塾」の面接対策レッソンを受けてみませんか？https://www.findjapanjob.com/juku「日本就職塾」は、日本企業へ就職したい外国人向けオンラインレッスンサービスです。講師は全員日本人。面接対策、履歴書・職務経歴書の書き方等をオンラインでレッスンします。</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div class="go_btn"><input type="submit" class="resume_ok_btn" value="応募する"></div>

                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <div class="popup" tabindex="-3" role="dialog" data-popup-id="privacyPopup">
        <div class="popup__container">
            <div class="popup__content">
                <div class="privacy_content" role="dialog" data-popup-id="demoPopup">
                    <h3>
                        <div class="popup__close"><i class="fa fa-times" aria-hidden="true"></i></div>自己紹介の映像を企業に公開する
                    </h3>
                    <div class="privacy_text2">
                        <p>応募する前に、一度「日本就職塾」の面接対策レッソンを受けてみませんか？https://www.findjapanjob.com/juku
                            「日本就職塾」は、日本企業へ就職したい外国人向けオンラインレッスンサービスです。講師は全員日本人。面接対策、履歴書・職務経歴書の書き方等をオンラインでレッスンします。</p>
                        <div class="pop_btn">
                            <input type="button" value="キャンセル" class="pop_cancel_btn" />
                            <input type="button" value="確定" class="pop_ok_btn" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var sub = 0;

        $('#popupTrigger3').popup();

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });

        $("#testForm").html5Validate(function() {
            if (sub == 1) {
                return false;
            }
            sub = 1;

            var recordStatus =  "{{ $data['recordStatus'] }}";
            if (recordStatus == 1) {
                var msg = "{{ config('code.alert_msg.web.record_job_exist') }}";
                var url = "{{route('web.record.index', ['record'])}}";
            } else if (recordStatus == 2) {
                var msg = "{{ config('code.alert_msg.web.scout_job_exist') }}";
                var url = "{{route('web.record.index', ['scout'])}}";
            }
            if (recordStatus > 0) {
                layer.open({
                    content: msg
                    ,btn: ['はい', 'いいえ']
                    ,yes: function(index){
                        layer.close(index);
                        window.location.href = url;
                    }
                    ,no: function(index){
                        layer.close(index);
                    }
                });
                sub = 0;
                return false;
            }

            var checkUser = $("input[name='resume_status']:checked").length;
            var video_url = "{{ $data['experienceInfo']['video_url'] }}";
            if (checkUser && !video_url) {
                layer.open({
                    content: '自己紹介の映像(1分)を追加しますか？'
                    ,btn: ['はい', 'いいえ']
                    ,yes: function(index){
                        layer.close(index);
                        window.location.href = "{{ route("web.experiences.uploadVideo") }}";
                    }
                    ,no: function(index){
                        $("input[name='resume_status']").iCheck('uncheck');
                        subForm();
                        layer.close(index);
                    }
                });
                sub = 0;
                return false;
            }

            subForm();


        }, {

        });

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });

        function subForm() {
            var job_id = "{{ $jobInfo['job_id'] }}";
            var note = $("textarea[name='note']").val();
            var resume_status = $("input[name='resume_status']:checked").length;
            $("body").mLoading({
                text:"送信中...",//加载文字
                icon:"/images/loading.gif",//加载图标
                mask:true//是否显示遮罩
            });
            $.ajax({
                type: "post",
                url: "{{ route('web.job.record') }}",
                dataType: "json",
                data: {"note": note, "resume_status": resume_status, "job_id": job_id},
                success: function(content){
                    layer.open({
                        content: content.msg,
                        btn: 'OK',
                        shadeClose: false,
                        yes: function(index) {
                            $("body").mLoading("hide");
                            layer.close(index);
                            if (content.status == 200) {
                                window.location.href = "{{ route('web.record.index', ['record']) }}";
                            }
                        }
                    });
                },
                error: function (err){
                    sub = 0;
                    console.log(err);
                }
            });
        }

    </script>

    <script type="text/javascript" src="{{asset('/js/jquery.autoTextarea.js')}}"></script>

@endsection