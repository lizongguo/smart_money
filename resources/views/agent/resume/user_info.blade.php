@extends('layouts.company')
@section('content')

    <div id="app">
        <div class="main">
            <div class="candidate_main">
                <h1><a href="javascript:history.back()" class="back_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> 戻る</a>登録者詳細</h1>
                <div class="candidate_content">
                    <div class="candidate_info_a">
                        <div class="candidate_info_list">
                            <h4>
                                <span>求職者ID：<b><{ recordInfo.account_code }></b></span>

                            </h4>
                            <h4>
                                <span>氏名：<b><{ recordInfo.name }></b></span>
                            </h4>
                            <br/>
                            <h4>
                                <span>システム管理ID：
                                    <input type="text" style="width: 40%;" class="agent_account_code input_text" v-model="recordInfo.agent_account_code">
                                <input type="button" value="確定" @click="message_save_status" class="pop_ok_btn" />
                                </span>
                            </h4>


                            <div class="candidate_info_d">
                                <h3></h3>
                                <ul>
                                    <li></li>
                                </ul>
                            </div>

                            <p><span>国籍：<b><{ recordInfo.nationality_id }></b></span><span>性別：<b><{ recordInfo.sex }></b></span><span>年齢：<b><{ recordInfo.birthday }></b></span></p>

                            <p><span>現住所：<b><{ recordInfo.address }></b></span><span>日本滞在年数：<b><{ recordInfo.address_extra_2 }></b></span></p>
                            <p><span>日本語：<b><{ recordInfo.jp_level }></b></span><span>英語レベル：<b><{ recordInfo.en_level }></b></span><span>TOEIC点数：<b><{ recordInfo.toeic }></b></span></p>

                            <p><span>Eメールアドレス：<b><{ recordInfo.email }></b></span><span>携帯電話：<b><{ recordInfo.cell_phone }></b></span></p>
                            <p><span>Line ID：<b><{ recordInfo.line_id }></b></span><span>WeChat ID：<b><{ recordInfo.wechat_id }></b></span><span>Skype ID：<b><{ recordInfo.skype_id }></b></span></p>
                            <p><span>最終学歴：<b><{ recordInfo.final_education }>[<{ recordInfo.university }>][<{ recordInfo.major }>]</b></span><span>文理区分：<b><{ recordInfo.science_arts }></b></span></p>
                            <p><span>就業状況：<b><{ recordInfo.employment_status }>（<{ recordInfo.employment_status_extra }>）</b></span><span>希望勤務地：<b><{ recordInfo.desired_place_ids }></b></span></p>
                            <p><span>ITスキル：<b><{ recordInfo.it_skill_str }></b></span></p>
                            <p><span>希望業種：<b><{ recordInfo.desired_fileds_str }></b></span></p>
                            <p><span>希望職種：<b><{ recordInfo.desired_job_type_str }></b></span></p>
                        </div>
                    </div>
                    <div class="candidate_info_d">
                        <h3>自己PR</h3>
                        <ul>
                            <li><span style="white-space: pre-line"><b><{ recordInfo.pr_other }></b></span></li>
                        </ul>
                    </div>
                    <div class="candidate_info_d">
                        <h3>推薦文</h3>
                        <ul>
                            <li><span style="white-space: pre-line"><b><{ recordInfo.recommendation }></b></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });
        var app = new Vue({
            delimiters: ['<{', '}>'],
            el: '#app',
            data: {
                statusAddUrl: "{{ route('agent.resume.saveStatus') }}",

                recordInfo: @json($recordInfo),
                resumeInfo: @json(config("code.resume")),

                status_data: {
                    record_id: 0,
                    status_user: 0,
                    memo: '書類要請中',
                },

            },
            mounted: function () {
                var _this = this;
                _this.status_data.record_id = _this.recordInfo.id;
            },
            methods: {
                message_save_status: function () {
                    var _this = this;

                    if (!_this.recordInfo.agent_account_code) {
                        $(".agent_account_code").testRemind("ご記入ください。");
                        $(".agent_account_code").focus();
                        return false;
                    }

                    ajaxApi(_this.statusAddUrl, {'user_id': _this.recordInfo.user_id, 'agent_account_code': _this.recordInfo.agent_account_code}, function (d) {
                        layer.open({
                            content: d.msg,
                            btn: 'OK',
                            shadeClose: false,
                            yes: function(index) {
                                layer.close(index);
                            }
                        });
                    });
                },
            }
        });
    </script>

@endsection