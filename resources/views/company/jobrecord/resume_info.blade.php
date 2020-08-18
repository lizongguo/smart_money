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
                                <img src="/images/no_photo.png"/>
                        </div>
                        <div class="candidate_info_list">
                            <h4><span>氏名：<b><{ recordInfo.name }></b></span></h4>
                            <p><span>国籍：<b><{ recordInfo.nationality_id }></b></span><span>性別：<b><{ recordInfo.sex }></b></span><span>年齢：<b><{ recordInfo.birthday }></b></span></p>
                            <p><span>現住所：<b><{ recordInfo.address }></b></span></p>
                            <p><span>日本語：<b><{ recordInfo.jp_level }></b></span><span>英語レベル：<b><{ recordInfo.en_level }></b></span></p>
                            <p><span>最終学歴：<b><{ recordInfo.final_education }>[<{ recordInfo.university }>][<{ recordInfo.major }>]</b></span><span>文理区分：<b><{ recordInfo.science_arts }></b></span></p>
                            <p><span>就業状況：<b><{ recordInfo.employment_status }>（<{ recordInfo.employment_status_extra }>）</b></span><span>希望勤務地：<b><{ recordInfo.desired_place_ids }></b></span></p>
                        </div>
                    </div>
                    <div class="candidate_info_d">
                        <h3>自己PR</h3>
                        <ul>
                            <li><span style="white-space: pre-line"><b><{ recordInfo.pr_other }></b></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var app = new Vue({
            delimiters: ['<{', '}>'],
            el: '#app',
            data: {
                recordInfo: @json($recordInfo),

            },
            mounted: function () {
                var _this = this;
            },
            methods: {

            }
        });
    </script>

@endsection