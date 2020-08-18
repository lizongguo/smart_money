@extends('layouts.company')
@section('content')

    <div id="app">
        <div class="main">
            <div class="candidate_main">
                <h1><a href="javascript:history.back()" class="back_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> 戻る</a>履歴書・職務経歴書</h1>
                <h2><span>応募者ID:<strong><{ recordInfo.account_code }></strong></span>
                    <span>最終更新日：<strong><{ recordInfo.updated_at }></strong></span>
                </h2>
                <div class="candidate_content">
                    <div class="candidate_info_a">
                        <div class="candidate_photo">
                            <template v-if="recordInfo.status_user > 2 && recordInfo.status_user != 4 && uInfo.photo">
                                <img :src="uInfo.photo"/>
                            </template>
                            <template v-else>
                                <img src="/images/no_photo.png"/>
                            </template>
                        </div>
                        <div class="candidate_info_list">
                            <h4><span>氏名：<b>
                                        <template v-if="recordInfo.status_user == 3"><{ recordInfo.name }></template>
                                        <template v-else>未公開</template>
                                    </b></span></h4>

                            <p><span>国籍：<b><{ recordInfo.nationality_id }></b></span><span>性別：<b><{ recordInfo.sex }></b></span><span>年齢：<b><{ recordInfo.birthday }></b></span></p>
                            <p><span>現住所：<b><{ recordInfo.address }></b></span></p>
                            <p><span>日本語：<b><{ recordInfo.jp_level }></b></span><span>英語レベル：<b><{ recordInfo.en_level }></b></span></p>
                            <p><span>最終学歴：<b><{ recordInfo.final_education }>[<{ recordInfo.university }>][<{ recordInfo.major }>]</b></span><span>文理区分：<b><{ recordInfo.science_arts }></b></span></p>
                            <p><span>就業状況：<b><{ recordInfo.employment_status }>（<{ recordInfo.employment_status_extra }>）</b></span><span>希望勤務地：<b><{ recordInfo.desired_place_ids }></b></span></p>
                        </div>
                    </div>
                    <div class="candidate_info_b">
                        <p><span>応募職種名：<b><a :href="'/jobdetail/'+jobInfo.job_id"><template v-if="jobInfo.job_name"><{ jobInfo.job_name }></template><template v-else ><{ jobInfo.position }></template></a></b></span></p>
                        <p><span>選考状況：<b><{ recordInfo.status_company_str }></b></span><span>自己紹介映像：<b><template v-if="uInfo.video_url && recordInfo.resume_status == 1"><a href="{{ route('company.record.video', [$recordInfo['id']]) }}" class="intr_link">有り</a></template><template v-else><a href="javascript:void(0)" class="intr_link">未公開</a></template></b></span></p>
                    </div>
                    <div class="candidate_info_d">
                        <h3>志望動機</h3>
                        <ul>
                            <li><span style="white-space: pre-line"><b><{ recordInfo.note }></b></span></li>
                        </ul>
                    </div>
                    <template v-if="recordInfo.status_company < 3 && recordInfo.status_user < 3">
                        <div class="candidate_info_c">
                            <template v-if="recordInfo.status_company == 1">
                                <p>未公開</p>
                                <input v-if="recordInfo.status_company == 1" @click="message_save_status" type="button" value="メッセージを送る" class="send_msg_btn"/>
                            </template>
                            <template v-else>
                                <p>書類要請中</p>
                            </template>
                        </div>
                    </template>
                    <template v-else-if="recordInfo.status_user == 4">
                        書類提出拒否
                    </template>

                    <template v-if="recordInfo.status_user > 2 && recordInfo.status_user != 4">
                        <template v-if="!uInfo.photo">
                            <div class="candidate_info_d">
                                <h3>プロフィル</h3>
                                <ul>
                                <li><span><b><a :href="uInfo.resume_1" download="履歴書のフォーマット">履歴書のフォーマット</a></b></span></li>
                                <li><span><b><a :href="uInfo.resume_2" download="職務経歴書のフォーマット">職務経歴書のフォーマット</a></b></span></li>
                                </ul>
                            </div>
                        </template>
                        <template v-else>
                            <div class="candidate_info_d">
                                <h3>プロフィル</h3>
                                <ul>
                                    <h4><{ uInfo.name }>(<{ uInfo.name_kana }>)</h4>
                                    <li><span>国籍：<b><{ uInfo.nationality_str }></b></span><span>性別：<b><{ uInfo.sex }></b></span><span>年齢：<b><{ uInfo.age }></b></span></li>
                                    <li><span>現住所：<b><{ uInfo.address_str }><{ uInfo.address_other }></b></span></li>
                                    <li><span>最寄駅：<b><{ uInfo.nearest_station }></b></span><span>電話：<b><{ uInfo.cell_phone }></b></span><span>Eメールアドレス：<b><{ uInfo.email }></b></span></li>
                                    <li><span>日本滞在年数/来日：<b><{ uInfo.japan_year }></b></span><span>ビザ種類：<b><{ uInfo.visa_type_str }></b></span><span>ビザ有効期限：<b><{ uInfo.visa_term }></b></span></li>
                                    <li><span>最終学歴：<b><{ recordInfo.final_education }>[<{ recordInfo.university }>][<{ recordInfo.major }>]</b></span></li>
                                    <li><span>就業状況：<b><{ recordInfo.employment_status }>（<{ recordInfo.employment_status_extra }>）</b></span><span>希望勤務地：<b><{ recordInfo.desired_place_ids }></b></span></li>
                                </ul>
                            </div>
                            <div class="candidate_info_e">
                                <h3>職務経歴［<{ uInfo.experiences.length }>社］</h3>
                                <ul v-for="(item) in uInfo.experiences">
                                    <li><span>期間：<b><{ item.start_date }>〜<template v-if="item.is_now">現在</template><template v-else><{item.end_date}></template></b></span></li>
                                    <li><span>会社名：<b><{item.corporate_name}></b></span><span>従業員数：<b><{item.employees_num}></b></span><span>雇用形態：<b><{item.employ_type}></b></span></li>
                                    <li><span>部署/役職：<b><{item.post_name}></b></span><span>業種：<b><template v-if="item.industry_name == 'その他'"><{item.industry_other}></template ><template v-else><{item.industry_name}></template></b></span><span>職種：<b><template v-if="item.occupation == 'その他'"><{item.occupation_other}></template ><template v-else><{item.occupation}></template></b></span></li>
                                    <li><span>担当業務：<b style="white-space: pre-line;"><{item.undertake_business}></b></span></li>
                                </ul>
                            </div>
                            <div class="candidate_info_d">
                                <h3>資格・免許</h3>
                                <ul>
                                    <li v-for="(item) in uInfo.qualification_and_license"><span><template v-if="resumeInfo.license_names[item.name] == 'その他'"><{item.certificate_other}></template><template v-else><{resumeInfo.license_names[item.name]}> : <b><template v-if="item.name == 1"><{resumeInfo.license_jlpt[item.level]}></template><template v-else><{item.point}>点<b></b></template></b></template></span></li>
                                </ul>
                            </div>
                            <div class="candidate_info_d">
                                <h3>語学スキル</h3>
                                <ul>
                                    <li><span>日本語：<b><{uInfo.jp_level_str}></b></span><span>英語：<b><{uInfo.en_level_str}></b></span></li>
                                    <li v-for="(item) in uInfo.language_skill"><span><{item.other_text}>：<b><{resumeInfo.other_level[item.other_level]}></b></span></li>
                                </ul>
                            </div>
                            <div class="candidate_info_d">
                                <h3>PC/ITスキル</h3>
                                <ul>
                                    <li>
                                        <span>OS：<b><{uInfo.skill_os}></b></span>
                                        <span>Office：<b><{uInfo.skill_office}></b></span>
                                    </li>
                                    <li>
                                        <span>デザイン(2D/3D)：</span>
                                    </li>
                                    <li v-for="(item) in uInfo.it_skill_graphic">
                                        <span><template v-if="item.name == 'その他'"><{item.other}></template><template v-else><{item.name}></template>:<b><{resumeInfo.residence_in_japan_year[item.year]}> - <{resumeInfo.residence_in_japan_month[item.month]}></b></span>
                                    </li>

                                    <li>
                                        <span>開発言語：</span>
                                    </li>
                                    <li v-for="(item) in uInfo.it_skill_language">
                                        <span><template v-if="item.name == 'その他'"><{item.other}></template><template v-else><{item.name}></template>:<b><{resumeInfo.residence_in_japan_year[item.year]}> - <{resumeInfo.residence_in_japan_month[item.month]}></b></span>
                                    </li>

                                    <li>
                                        <span>DB：</span>
                                    </li>
                                    <li v-for="(item) in uInfo.it_skill_db">
                                        <span><template v-if="item.name == 'その他'"><{item.other}></template><template v-else><{item.name}></template>:<b><{resumeInfo.residence_in_japan_year[item.year]}> - <{resumeInfo.residence_in_japan_month[item.month]}></b></span>
                                    </li>

                                    <li>
                                        <span>フレームワーク：</span>
                                    </li>
                                    <li v-for="(item) in uInfo.it_skill_framework">
                                        <span><template v-if="item.name == 'その他'"><{item.other}></template><template v-else><{item.name}></template>:<b><{resumeInfo.residence_in_japan_year[item.year]}> - <{resumeInfo.residence_in_japan_month[item.month]}></b></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="candidate_info_d">
                                <h3>自己PR</h3>
                                <ul>
                                    <li><span><b style="white-space: pre-line"><{ recordInfo.pr_other }></b></span></li>
                                </ul>
                            </div>
                        </template>
                        <div class="candidate_info_d" v-if="uInfo.recommendation">
                            <h3>推薦文</h3>
                            <ul>
                                <li><span><b style="white-space: pre-line"><{uInfo.recommendation}></b></span></li>
                            </ul>
                        </div>
                    </template>
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
                statusAddUrl: "{{ route('company.record.memoAdd') }}",

                recordInfo: @json($recordInfo),
                jobInfo: @json($jobInfo),
                uInfo: @json($uInfo),
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
                    _this.status_data.status_user = 2;

                    ajaxApi(_this.statusAddUrl, _this.status_data, function (d) {
                        layer.open({
                            content: d.msg,
                            btn: 'OK',
                            shadeClose: false,
                            yes: function(index) {
                                layer.close(index);
                                if (d.status == 200) {
                                    window.location.reload();
                                }
                            }
                        });
                    });
                },
            }
        });
    </script>

@endsection