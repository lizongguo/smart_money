@extends('layouts.web')
@section('content')
    <div class="main">
        <div class="view_body">
            <div class="view_title">
                <h1>{{$item['position']}}</h1>
            </div>
            <div class="view_top_btn">
                @if(!$preview)
                <div class="">
                    <span><a href="javascript:void(0)" onclick="fav({{$item['job_id']}})" class="fav_icon @if($item['favorite']) fav_icon_active @endif"><i class="fa fa-heart-o"></i></a></span>
                    <a href="javascript:void(0)" onclick="record({{$item['job_id']}})" class="accept_btn">応募する</a>
                </div>
                @endif
            </div>

            <div class="view_list">
                <ul>
                    @if($item['job_code'])
                    <li>
                        <div class="text_item"><span>求人管理ID</span></div>
                        <div class="text_box">{{$item['account_code']}}</div>
                    </li>
                    @endif
                    @if($item['job_name'])
                    <li>
                        <div class="text_item"><span>募集職種名</span></div>
                        <div class="text_box">{{$item['job_name']}}</div>
                    </li>
                    @endif
                    @if($item['job_category'])
                    <li>
                        <div class="text_item"><span>職種カテゴリ</span></div>
                        <div class="text_box">@if($item['job_category'] != 99)
                            {{config('code.resume.desired_job_type')[$item['job_category']]}}
                                                  @else
                                                  {{$item["job_other"]}}
                            @endif</div>
                    </li>
                    @endif

                        <li>
                            <div class="text_item"><span>仕事内容</span></div>
                            <div class="text_box">{{$item['jp_detail']}}</div>
                        </li>

                        @if($item['company_id'])
                            @if($item['company_info']["company_type"])
                                <li>
                                    <div class="text_item"><span>会社の種類</span></div>
                                    <div class="text_box">{{config('code.resume.company_type')[$item['company_info']["type"]]}}</div>
                                </li>
                            @endif
                            @if($item['company_info']["found_date"])
                                <li>
                                    <div class="text_item"><span>会社設立年月</span></div>
                                    <div class="text_box">{{date("Y年m月", strtotime($item['company_info']["found_date"]))}}</div>
                                </li>
                            @endif
                            @if($item['company_info']["capital"])
                                <li>
                                    <div class="text_item"><span>資本金</span></div>
                                    <div class="text_box">{{$item['company_info']["capital"]}}</div>
                                </li>
                            @endif
                            @if($item['company_info']["member_total"])
                                <li>
                                    <div class="text_item"><span>社員数</span></div>
                                    <div class="text_box">{{config('code.company.member_total')[$item['company_info']["member_total"]] }}</div>
                                </li>
                            @endif
                            @if($item['company_info']["foreign_member_note"])
                                <li>
                                    <div class="text_item"><span>外国人社員の活躍について</span></div>
                                    <div class="text_box">{{$item['company_info']["foreign_member_note"]}}</div>
                                </li>
                            @endif
                            @if($item['company_info']["fileds"])
                                <li>
                                    <div class="text_item"><span>業種</span></div>
                                    <div class="text_box">@if($item['company_info']["fileds"] == 99) {{ $item['company_info']["fileds_other"] }} @else{{config('code.resume.desired_fileds')[$item['company_info']["fileds"]]}} @endif</div>
                                </li>
                            @endif
                            @if($item['company_info']["company_summary"])
                                <li>
                                    <div class="text_item"><span>会社概要</span></div>
                                    <div class="text_box">{{$item['company_info']["company_summary"]}}</div>
                                </li>
                            @endif
                            @if($item['company_info']["company_bussiness"])
                                <li>
                                    <div class="text_item"><span>事業内容</span></div>
                                    <div class="text_box">{{$item['company_info']["company_bussiness"]}}</div>
                                </li>
                            @endif
                        @else

                        @endif

                    @if($item['jp_level_2'] || $item['jp_level'] || $item['en_level'] || $item['en_level_other'])
                    <li>
                        <div class="text_item"><span>応募資格</span></div>
                        <div class="text_box">@if($item['jp_level_2'] || $item['jp_level'])日本語レベル：{{config('code.resume.jp_level_2')[$item['jp_level_2']]}} {{config('code.resume.jp_level')[$item['jp_level']]}}@endif

                            @if($item['en_level'])英語レベル：{{config('code.resume.en_level')[$item['en_level']]}}@endif

                            @if($item['en_level_other']){{$item['en_level_other']}}@endif</div>
                    </li>
                    @endif

                        @if($item['target'] || $item['nationality_other'])
                            <li>
                                <div class="text_item"><span>募集対象</span></div>
                                <div class="text_box">@if($item['target'])@foreach( explode(',', $item['target']) as $v){{config('code.resume.target')[$v]}}
                                    @endforeach @endif
                                    @if($item['nationality_other']){{$item['nationality_other']}}籍大歓迎@endif
                                </div>
                            </li>
                        @endif

                        @if($item['age_start'] || $item['age_end'])
                            <li>
                                <div class="text_item"><span>対象年齢</span></div>
                                <div class="text_box">@if($item['age_start'] > 0 && $item['age_end'] > 0){{$item['age_start']}}歳～{{$item['age_end']}}歳
                                    @elseif($item['age_start'] > 0 && $item['age_end'] <= 0)
                                        {{$item['age_start']}}歳～
                                    @elseif($item['age_start'] <= 0 && $item['age_end'] > 0)
                                        ～{{$item['age_end']}}歳
                                    @endif</div>
                            </li>
                        @endif

                        @if($item['age_other'])
                            <li>
                                <div class="text_item"><span>年齢制限理由</span></div>
                                <div class="text_box">{{$item['age_other']}}</div>
                            </li>
                        @endif

                        @if($item['employment_overseas'])
                            <li>
                                <div class="text_item"><span>海外在住者の採用</span></div>
                                <div class="text_box">有り</div>
                            </li>
                        @endif

                        @if($item['working_visa'])
                            <li>
                                <div class="text_item"><span>就労ビザのサポート</span></div>
                                <div class="text_box">有り</div>
                            </li>
                        @endif

                    <li>
                        <div class="text_item"><span>雇用形態</span></div>
                        <div class="text_box">@foreach( explode(',', $item['working_form']) as $v){{config('code.resume.working_form')[$v]}}
                            @endforeach</div>
                    </li>

                        @if(($item['yearly_income_low'] || $item['yearly_income_up']) || ($item['monthly_income_low'] || $item['monthly_income_up']) || ($item['hourly_from'] || $item['hourly_to']) || $item['yearly_income_memo'])
                            <li>
                                <div class="text_item"><span>給与</span></div>
                                <div class="text_box">@if($item['yearly_income_low'] && $item['yearly_income_up'])
                                        年俸：{{config('code.resume.wage_arr_1')[$item['yearly_income_low']]}}～@if($item['yearly_income_up'] != 9999){{config('code.resume.wage_arr_1')[$item['yearly_income_up']]}}@endif

                                    @elseif($item['yearly_income_low'] && !$item['yearly_income_up'])
                                        年俸：{{config('code.resume.wage_arr_1')[$item['yearly_income_low']]}}～
                                    @elseif(!$item['yearly_income_low'] && $item['yearly_income_up'] && $item['yearly_income_up'] != 9999)
                                        年俸：～{{config('code.resume.wage_arr_1')[$item['yearly_income_up']]}}
                                    @endif
                                    @if($item['monthly_income_low'] && $item['monthly_income_up'])
                                        月給：{{config('code.resume.wage_arr_2')[$item['monthly_income_low']]}}～@if($item['monthly_income_up'] != 9999){{config('code.resume.wage_arr_2')[$item['monthly_income_up']]}}@endif

                                    @elseif($item['monthly_income_low'] && !$item['monthly_income_up'])
                                        月給：{{config('code.resume.wage_arr_2')[$item['monthly_income_low']]}}～
                                    @elseif(!$item['monthly_income_low'] && $item['monthly_income_up'] && $item['monthly_income_up'] != 9999)
                                        月給：～{{config('code.resume.wage_arr_2')[$item['monthly_income_up']]}}
                                    @endif
                                    @if($item['hourly_from'] && $item['hourly_to'])
                                        時給：{{config('code.resume.wage_arr_3')[$item['hourly_from']]}}～@if($item['hourly_to'] != 9999){{config('code.resume.wage_arr_3')[$item['hourly_to']]}}@endif

                                    @elseif($item['hourly_from'] && !$item['hourly_to'])
                                        時給：{{config('code.resume.wage_arr_3')[$item['hourly_from']]}}～
                                    @elseif(!$item['hourly_from'] && $item['hourly_to']  && $item['hourly_to'] != 9999)
                                        時給：～{{config('code.resume.wage_arr_3')[$item['hourly_to']]}}
                                    @endif
                                    @if($item['yearly_income_memo'])
                                        {{$item['yearly_income_memo']}}
                                    @endif
                                </div>
                            </li>
                        @endif


                    @if($item['prefecture'] || $item['working_place'] ||$item['working_place_other'])
                        <li>
                            <div class="text_item"><span>勤務地</span></div>
                            <div class="text_box">@if($item['prefecture']) @foreach(explode(',', $item['prefecture']) as $v)
                                @if($v == 99) {{$item['prefecture_other']}}@else {{config('code.resume.country_city')[$v]}}@endif @endforeach

                          @endif
                                @if($item['working_place'])
                                {{$item['working_place']}}
                            @endif
                                @if($item['working_place_other'])
                                {{$item['working_place_other']}}
                                @endif</div></li>
                    @else

                    @endif

                    @if($item['working_time_all'])
                    <li>
                        <div class="text_item"><span>勤務時間</span></div>
                        <div class="text_box">{{$item['working_time_all']}}</div>
                    </li>
                    @endif
                    @if($item['working_time_holiday'])
                    <li>
                        <div class="text_item"><span>休日・休暇</span></div>
                        <div class="text_box">{{$item['working_time_holiday']}}</div>
                    </li>
                    @endif

                    @if($item['welfare'])
                    <li>
                        <div class="text_item"><span>福利厚生</span></div>
                        <div class="text_box">{{$item['welfare']}}</div>
                    </li>
                    @endif
                    @if($item['interview_process'])
                    <li>
                        <div class="text_item"><span>選考プロセス</span></div>
                        <div class="text_box">{{$item['interview_process']}}</div>
                    </li>
                    @endif
                    @if($item['others'])
                        <li>
                            <div class="text_item"><span>その他</span></div>
                            <div class="text_box">{{$item['others']}}</div>
                        </li>
                    @endif

                </ul>
            </div>
            @if(!$preview)
                <div class="view_bottom_btn">
                    <div class="">
                        <a href="javascript:void(0)" onclick="record({{$item['job_id']}})" class="accept_btn">応募する</a>
                        {{--<a href="#" class="fav_icon"><i class="fa fa-heart-o"></i></a>--}}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>

        var userId = "{{$userInfo->id}}";
        var recordStatus = "{{$item['record']}}";
        if (recordStatus == 1) {
            var msg = "{{ config('code.alert_msg.web.record_job_exist') }}";
            var url = "{{route('web.record.index', ['record'])}}";
        } else if (recordStatus == 2) {
            var msg = "{{ config('code.alert_msg.web.scout_job_exist') }}";
            var url = "{{route('web.record.index', ['scout'])}}";
        }

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });

        function record() {
            if (!userId) {
                layer.open({
                    content: 'ログインしてください。'
                    ,btn: ['はい', 'いいえ']
                    ,yes: function(index){
                        layer.close(index);
                        window.location.href = "{{route('web.index.login')}}";
                    }
                    ,no: function(index){
                        layer.close(index);
                    }
                });
                return false;
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
                return false;
            }

            window.location.href = "{{ route("web.job.record", [$item['job_id']]) }}";
        }

        function fav(job_id) {
            if (!userId) {
                layer.open({
                    content: 'ログインしてください。'
                    ,btn: ['はい', 'いいえ']
                    ,yes: function(index){
                        layer.close(index);
                        window.location.href = "{{route('web.index.login')}}";
                    }
                    ,no: function(index){
                        layer.close(index);
                    }
                });
                return false;
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
                return false;
            }

            $.ajax({
                type: "post",
                url: "{{ route('web.favorite.favorite') }}",
                dataType: "json",
                data: {'user_id': userId, 'job_id': job_id},
                success: function(content){
                    $(".fav_icon").toggleClass("fav_icon_active");
                    layer.open({
                        content: content.msg,
                        skin: 'msg',
                        time: 2
                    });
                },
                error: function (err){
                    console.log(err);
                }
            });

        }
    </script>

@endsection