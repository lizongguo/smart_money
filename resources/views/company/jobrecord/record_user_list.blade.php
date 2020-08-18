@extends('layouts.company')
@section('content')

    <link type="text/css" href="{{asset('/css/formSelects-v4.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('/js/formSelects-v4.min.js')}}"></script>

    <div id="app">
        <div class="main">
            <div class="company_main company_main_list">
                <div class="company_body">
                    <div class="company_item_info">
                        <span>求人管理ID：<b>{{ $jobInfo['account_code'] ? $jobInfo['account_code'] : $jobInfo['job_id'] }}</b></span>
                        <span>システム管理ID：<b>{{ $jobInfo['job_code'] }}</b></span>
                        <span>募集職種名：<b>{{ $jobInfo['job_name'] }}</b></span>
                        <span>状況：<b>@if($jobInfo['job_period_type'] == 1)
                                    @if($jobInfo['job_period_start'] <= date("Y-m-d") && $jobInfo['job_period_end'] >= date("Y-m-d"))
                                        掲載中
                                    @elseif($jobInfo['job_period_start'] > date("Y-m-d"))
                                        掲載準備中
                                    @elseif($jobInfo['job_period_end'] < date("Y-m-d"))
                                        掲載終了
                                    @endif
                                @elseif($jobInfo['job_period_type'] == 2)
                                    掲載中
                                @else
                                    掲載中止
                                @endif</b></span>
                        {{--<span>掲載期間：<b>@if($jobInfo['job_period_type'] == 1)--}}
                                    {{--{{ $jobInfo['job_period_start'] }}  -  {{ $jobInfo['job_period_end'] }}--}}
                                {{--@elseif($jobInfo['job_period_type'] == 2)--}}
                                    {{--無期限--}}
                                {{--@else--}}
                                    {{--掲載中止--}}
                                {{--@endif</b></span>--}}
                        <a href="javascript:history.back()" class="back_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> 戻る</a>
                    </div>

                    <form id="testForm" method="get">
                        <div class="company_list_title">
                            <h3>@if($type == 'record') 応募者一覧 @else スカウト一覧 @endif
                            </h3>
                            <div class="search_main agent_search">
                                <div class="search_box">
                                    <div class="search_input">
                                        <input type="text" readonly class="search_text" name="search_text_str" v-model="sh.search_text_str"/>
                                        <a class="search_clear"><i class="fa fa-times"></i></a>
                                        <span class="search_choose" id="popupTrigger" data-popup-target="searchPopup">選択 <i class="fa fa-chevron-down fi-arrow-down"></i></span></div>
                                </div>
                                <div class="search_btn_box">
                                    <input type="button" value="@if($type == 'record') 応募者検索 @else 絞り込み検索 @endif" @click="search_list" class="search_btn" />
                                </div>
                            </div>
                                <div class="btn_bar">
                                    <span>
                                    @if($type != 'record')
                                    <a href="{{ route('company.scout.user', [$jobInfo['job_id']]) }}" class="change_btn">+</a>
                                    @endif
                                    <a href="javascript:void(0)" @click="change_status_invite" class="change_btn">書類開示要請</a>
                                    <a href="javascript:void(0)" @click="change_status" class="change_btn">選考状況変更</a>
                                    </span>
                                </div>
                        </div>

                        <div class="popup" tabindex="-1" role="dialog" data-popup-id="searchPopup">
                            <div class="popup__container">

                                <input type="hidden" name="sex_arr">
                                <input type="hidden" name="address_arr">
                                <input type="hidden" name="employment_status_arr">
                                <input type="hidden" name="science_arts_arr">
                                <input type="hidden" name="final_education_arr">
                                <input type="hidden" name="jp_level_2_arr">

                                <div class="popup__content">
                                    <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                                        <h3>
                                            <div class="popup__close"><i class="fa fa-times" aria-hidden="true"></i></div>
                                            <div class="storage_clear" @click="clear_button"><i class="fa fa-refresh" aria-hidden="true"></i>リセット</div>選択
                                        </h3>
                                        <ul>
                                            <li class="item_title"><span>日本語</span></li>
                                            <li class="checkbox_list">
                                                <template v-for="(item,k) in config_jp_level_2" v-if="k>0">
                                                    <span><input type="checkbox" v-model="sh.jp_level_2" name="jp_level_2" :value="k"><label><{ item }></label></span>
                                                </template>
                                            </li>
                                            <li class="resume_item_title">国籍(地域)<span>＊</span></li>
                                            <li class="country_select">
                                                <div class="select">
                                                    <select v-model="sh.nationality_id" name="nationality_id">
                                                        <option value="" selected>選択する</option>
                                                        <option v-for="(item, k) in config_nationality_id" :value="k" v-if="k > 0"><{ item }></option>
                                                    </select>
                                                </div>
                                                <div class="input_box">
                                                    <input type="text" v-show="sh.nationality_id == 17" class="input_text" v-model="sh.nationality" name="nationality" placeholder="その他" />
                                                </div>
                                            </li>
                                            <li class="item_title"><span>性別</span></li>
                                            <li class="checkbox_list">
                                                <template v-for="(item,k) in config_sex" v-if="k>0">
                                                    <span><input type="checkbox" v-model="sh.sex" name="sex" :value="k"><label><{ item }></label></span>
                                                </template>
                                            </li>
                                            <li class="item_title"><span>年齢</span></li>
                                            <li class="all_wages">
                                                <div class="select">
                                                    <select v-model="sh.age_start" name="age_start">
                                                        <option value="" selected>選択する</option>
                                                        <option v-for="(item, k) in config_age" :value="item"><{ item }>歳</option>
                                                    </select>
                                                </div>
                                                <div class="select">
                                                    <select v-model="sh.age_end" name="age_end">
                                                        <option value="" selected>選択する</option>
                                                        <option v-for="(item, k) in config_age" :value="item"><{ item }>歳</option>
                                                    </select>
                                                </div>
                                                <span class="select_middle">-</span>
                                            </li>
                                            <li class="item_title"><span>現住所</span></li>
                                            <li class="checkbox_list">
                                                <template v-for="(item,k) in config_address" v-if="k>0">
                                                    <span><input type="checkbox" v-model="sh.address" name="address" :value="k"><label><{ item }></label></span>
                                                </template>
                                            </li>
                                            <li class="item_title"><span>就業状況</span></li>
                                            <li class="checkbox_list">
                                                <template v-for="(item,k) in config_employment_status" v-if="k>0">
                                                    <span><input type="checkbox" v-model="sh.employment_status" name="employment_status" :value="k"><label><{ item }></label></span>
                                                </template>
                                            </li>
                                            <li class="item_title"><span>文理区分</span></li>
                                            <li class="checkbox_list">
                                                <template v-for="(item,k) in config_science_arts" v-if="k>0">
                                                    <span><input type="checkbox" v-model="sh.science_arts" name="science_arts" :value="k"><label><{ item }></label></span>
                                                </template>
                                            </li>
                                            <li class="item_title"><span>最終学歴</span></li>
                                            <li class="checkbox_list">
                                                <template v-for="(item,k) in config_final_education" v-if="k>0">
                                                    <span><input type="checkbox" v-model="sh.final_education" name="final_education" :value="k"><label><{ item }></label></span>
                                                </template>
                                            </li>
                                        </ul>
                                        <div class="pop_btn">
                                            <input type="button" value="キャンセル" class="pop_cancel_btn" />
                                            <input type="button" value="確定" @click="search_text" class="pop_ok_btn" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="company_management_main">
                        <table class="company_management_table">
                            <thead>
                            <tr class="company_management_title">
                                <th class="check_box_item nosort">
                                    <input type="checkbox" id="checkbox_all" v-model="sh.check_all" name="check_all" value="1">
                                </th>
                                <th class="">@if($type != 'record') スカウト日 @else 応募日 @endif</th>
                                <th class="">@if($type != 'record') 求職者ID @else 応募者ID @endif</th>
                                <th class="">氏名</th>
                                <th class="">国籍</th>
                                <th class="">性別</th>
                                <th class="">年齢</th>
                                <th class="">現住所</th>
                                <th class="">日本語</th>
                                <th class="stay_year_item">
                                    <p>来日年数</p>
                                    <p>（日本滞在年数）</p>
                                </th>
                                <th class="">就業状況</th>
                                <th class="">文理区分</th>
                                <th class="">最終学歴</th>
                                {{--<th class="">自己紹介映像</th>--}}
                                <th class="">選考状況</th>
                                <th class="">メッセージ</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $k => $v)
                                <tr>
                                    <td>
                                        <input type="checkbox" v-model="sh.record_id" name="record_id" value="{{ $v['id'] }}" class="check_box">
                                    </td>
                                    <td>
                                        {{ $v['job_record_created_at'] }}
                                    </td>
                                    <td>
                                        {{ $v['account_code'] ? $v['account_code'] : $v['user_id'] }}
                                    </td>
                                    <td>
                                        <a href="{{ route('company.record.user_info', [$v['id']]) }}">{{ ($v['status_user'] > 2 && $v['status_user'] != 4) ? $v['name'] : '未開示'}}</a>
                                    </td>
                                    <td>
                                        {{ $v['nationality_id'] }}
                                    </td>
                                    <td>
                                        {{ $v['sex'] }}
                                    </td>
                                    <td>
                                        {{ $v['birthday'] }}
                                    </td>
                                    <td>
                                        {{ $v['address'] }}
                                    </td>
                                    <td>
                                        {{ $v['jp_level'] }}
                                    </td>
                                    <td>
                                        {{ $v['address_extra_2'] }}
                                    </td>
                                    <td>
                                        {{ $v['employment_status'] }}
                                    </td>
                                    <td>
                                        {{ $v['science_arts'] }}
                                    </td>
                                    <td>
                                        {{ $v['final_education'] }}
                                    </td>
                                    {{--<td>--}}
                                        {{--<a href="#" class="have_btn">{{ $v['final_education'] }}</a>--}}
                                    {{--</td>--}}
                                    <td>
                                        <a @click="status_user_click({{$v['id']}})">{{ $v['status_company_str'] }}</a>
                                    </td>
                                    <td>
                                        <div class="msg_btn" @click="status_message_click({{$v['id']}})">一覧@if($v['read_company'])<span>{{ $v['read_company'] }}</span>@endif</div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <form id="testForm1" action="" method="post" accept-charset="UTF-8" name="testForm">
                            <div class="popup" tabindex="-1" role="dialog" data-popup-id="optionPopup" id="optionPopup">
                                <div class="popup__container">


                                    <div class="popup__content">
                                        <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                                            <h3>
                                                <div class="popup__close" @click="closePop1"><i class="fa fa-times" aria-hidden="true"></i></div>選考状況を変更します。
                                            </h3>
                                            <ul>
                                                <template v-if="!buttonStatus">
                                                <li class="item_title"><span>選考状況</span></li>
                                                <li>
                                                    <div class="select">
                                                        <select v-model="arr.status_user" required>
                                                            <option value="0" selected>選択する</option>
                                                            <option v-for="(item, k) in status_by_id" :value="k" v-if="k > 5"><{ item }></option>
                                                        </select>
                                                    </div>
                                                </li>
                                                </template>

                                                <li class="item_title"><span>メモ</span></li>
                                                <li><textarea maxlength="1000" class="text_area" v-model="arr.memo"></textarea></li>
                                            </ul>
                                            <div class="pop_btn">
                                                <template v-if="!buttonStatus">
                                                    <input type="button" value="キャンセル" class="pop_cancel_btn"  @click="closePop1" />
                                                    <input type="button" value="登録" class="pop_ok_btn" @click="memo_save"/>
                                                </template>

                                                <template v-else>
                                                    <input type="button" v-for="(item, k) in status_by_id" :value="item" class="pop_ok_btn" @click="statusButton(k)" />
                                                </template>
                                            </div>
                                            <div class="state_records" v-if="!check_all_button">
                                                <li class="line_title">
                                                    <hr>
                                                    <p><span>履歴</span></p>
                                                </li>
                                                <ul>
                                                    <li v-for="(item, i) in memoListArr">
                                                        <p class="state_records_date"><span class="state_records_date_item"><{item.created_at}></span><span class="state_records_text"><{config_record.status_company[item.status_company]}></span></p>
                                                        <p class="state_records_info"><span class="state_records_info_tit">メモ：</span><span class="state_records_info_text"><{ item.memo }></span></p>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        var recordType = "{{ $type }}";
        var recordTypeStr;
        if (recordType != 'record') {
            recordTypeStr = "求職者ID";
        } else {
            recordTypeStr = "応募者ID";
        }

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });

        var app = new Vue({
            delimiters: ['<{', '}>'],
            el: '#app',
            data: {
                sh: @json($data),

                saveUrl: "{{ route('company.record.memoAdd') }}",
                listUrl: "{{ route('company.record.statusList') }}",
                memoListUrl: "{{ route('company.record.memoList') }}",
                statusAddUrl: "{{ route('company.record.statusAdd') }}",

                search_text_arr: [],

                user_list: [],

                config_age: [],
                config_jp_level_2: @json(config("code.resume.jp_level_2")),
                config_nationality_id: @json(config("code.resume.nationality")),
                config_sex: @json(config("code.resume.sex")),
                config_address: @json(config("code.resume.address")),
                config_employment_status: @json(config("code.resume.employment_status")),
                config_science_arts: @json(config("code.resume.science_arts")),
                config_final_education: @json(config("code.resume.final_education")),
                statusArr: @json($statusArr),
                accountCodeArr: @json($accountCodeArr),

                sub_record_id: '',
                arr: {
                    record_id: 0,
                    status_user: 0,
                    memo: '',
                },
                message: {
                    record_id: 0,
                    title: '',
                    content: '',
                },

                config_record: @json(config("code.record")),

                listArr: [],
                memoListArr: [],
                recordInfo: [],
                jobInfo: [],

                check_all_button: false,

                status_by_id: [],
                buttonStatus: 0,
            },
            mounted: function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                });

                var _this = this;
                $("input").on('ifChecked',function(event) {
                    var name = event.target.getAttribute('name');
                    if(event.currentTarget.checked) {
                        _this.sh[name].push(event.currentTarget.value);
                    }
                    if (name == "check_all") {
                        _this.checkbox_all();
                    }
                    if (name == "record_id") {
                        _this.checkbox_click();
                    }
                });

                $("input").on('ifUnchecked',function(event) {
                    var name = event.target.getAttribute('name');
                    if(!event.currentTarget.checked) {
                        _this.sh[name].splice(_this.sh[name].indexOf(event.currentTarget.value),1);
                    }
                    if (name == "check_all") {
                        _this.checkbox_all();
                    }
                    if (name == "record_id") {
                        _this.checkbox_click();
                    }
                });

                for (var i=18; i<=50; i++) {
                    _this.config_age.push(i);
                }

                _this.sh.record_id = [];
                _this.sh.check_all = [];
                _this.status_by_id = _this.config_record.status_company;

            },
            methods: {
                search_text: function () {
                    var _this = this;
                    $("input[name='sex_arr']").val(_this.sh.sex.join(','));
                    $("input[name='address_arr']").val(_this.sh.address.join(','));
                    $("input[name='employment_status_arr']").val(_this.sh.employment_status.join(','));
                    $("input[name='science_arts_arr']").val(_this.sh.science_arts.join(','));
                    $("input[name='final_education_arr']").val(_this.sh.final_education.join(','));
                    $("input[name='jp_level_2_arr']").val(_this.sh.jp_level_2.join(','));


                    // if (_this.sh.jp_level_2) {
                    //     _this.search_text_arr.push( _this.config_jp_level_2[_this.sh.jp_level_2]);
                    // }

                    if (_this.sh.nationality_id) {
                        if ( _this.config_nationality_id[_this.sh.nationality_id] == 'その他' && _this.sh.nationality) {
                            _this.search_text_arr.push(_this.sh.nationality);
                        } else {
                            _this.search_text_arr.push(_this.config_nationality_id[_this.sh.nationality_id]);
                        }
                    }

                    var sex_str = "";
                    _this.sh.sex.forEach(function (item, index) {
                        sex_str += _this.config_sex[item];
                    });
                    if (sex_str) {
                        _this.search_text_arr.push(sex_str);
                    }

                    var age;
                    if (_this.sh.age_start && _this.sh.age_end) {
                        age = _this.sh.age_start + '歳 〜 ' + _this.sh.age_end + "歳";
                    } else if (_this.sh.age_start && !_this.sh.age_end) {
                        age = _this.sh.age_start + '歳' ;
                    } else if (!_this.sh.age_start && _this.sh.age_end) {
                        age = '〜' + _this.sh.age_end + "歳";
                    }
                    if (age) {
                        _this.search_text_arr.push(age);
                    }

                    var address_str = "";
                    _this.sh.address.forEach(function (item, index) {
                        address_str += _this.config_address[item];
                    });
                    if (address_str) {
                        _this.search_text_arr.push(address_str);
                    }

                    var employment_status_str = "";
                    _this.sh.employment_status.forEach(function (item, index) {
                        employment_status_str += _this.config_employment_status[item];
                    });
                    if (employment_status_str) {
                        _this.search_text_arr.push(employment_status_str);
                    }

                    var science_arts_str = "";
                    _this.sh.science_arts.forEach(function (item, index) {
                        science_arts_str += _this.config_science_arts[item];
                    });
                    if (science_arts_str) {
                        _this.search_text_arr.push(science_arts_str);
                    }

                    var final_education_str = "";
                    _this.sh.final_education.forEach(function (item, index) {
                        final_education_str += _this.config_final_education[item];
                    });
                    if (final_education_str) {
                        _this.search_text_arr.push(final_education_str);
                    }

                    var jp_level_2_str = "";
                    _this.sh.jp_level_2.forEach(function (item, index) {
                        jp_level_2_str += _this.config_jp_level_2[item];
                    });
                    if (jp_level_2_str) {
                        _this.search_text_arr.push(jp_level_2_str);
                    }

                    _this.sh.search_text_str = _this.search_text_arr.splice("、");
                },
                clear_button: function () {
                    var _this = this;
                    $('input').iCheck('uncheck');
                    $('select').prop('selectedIndex', '0');
                    _this.sh.search_text_str = '';
                    _this.sh.jp_level_2 = '';
                    _this.sh.age_start = '';
                    _this.sh.age_end = '';
                    _this.sh.nationality_id= '';
                    _this.sh.nationality= '';
                },

                search_list: function () {
                    $("#testForm").submit();
                },

                status_user_click: function (id) {
                    var _this = this;
                    _this.arr.record_id = id;
                    _this.memo_list();
                    _this.check_all_button = false;
                    $("#optionPopup").addClass("opened");
                },

                closePop1: function (obj) {
                    var _this = this;
                    _this.arr.status_user = '0';
                    _this.arr.memo = '';
                    $(obj.target).parents('.popup').removeClass('opened');
                },

                status_message_click: function (id) {
                    window.location.href = "{{ route('company.record.statusList', []) }}/" + id;
                },

                change_status: function () {
                    var _this = this;
                    if (_this.sh.record_id.length <= 0) {
                        layer.open({
                            content: "選択してから操作してください。",
                            skin: 'msg',
                            time: 2
                        });
                        return false;
                    }
                    _this.arr.record_id = _this.sh.record_id;
                    _this.check_all_button = true;
                    _this.buttonStatus = 0;
                    _this.status_by_id = _this.config_record.status_company;

                    $("#optionPopup").addClass("opened");
                },

                checkbox_all: function () {
                    var _this = this;
                    console.log(_this.sh.check_all.length);
                    if(_this.sh.check_all.length != 1){
                        $("input[name='record_id']").iCheck('uncheck');
                    } else {
                        $("input[name='record_id']").iCheck('check');
                    }
                },
                checkbox_click: function () {
                    var _this = this;
                    var listLenght = "{{ count($list) }}";
                    if(_this.sh.record_id.length == listLenght){
                        $("input[name='check_all']").iCheck('check');
                    } else {
                        //$("input[name='check_all']").iCheck('uncheck');
                    }
                },

                memo_list: function () {
                    var _this = this;
                    ajaxApi(_this.memoListUrl, _this.arr, function (d) {
                        _this.memoListArr = d.list;
                        _this.status_by_id = d.statusList;
                        _this.buttonStatus = d.buttonStatus;
                    });
                },

                statusButton: function (key) {
                    var _this = this;
                    _this.arr.status_user = key;
                    _this.memo_save();
                },

                memo_save: function () {
                    var _this = this;

                    if (_this.status_by_id.length < 1) {
                        layer.open({
                            content: "変更できる選考状況がありません。",
                            skin: 'msg',
                            time: 2
                        });
                        return false;
                    }
                    var sub = true;

                    var errorUserId = [];
                    if (_this.check_all_button) {
                        _this.arr.record_id.forEach(function (item, index) {
                            if (typeof _this.statusArr[item] == "undefined" || _this.statusArr[item] <= 3) {
                                errorUserId.push('[' + _this.accountCodeArr[item] + ']');
                            }
                        });
                        if (errorUserId.length > 0) {
                            var codeMsg = "{{ config('code.alert_msg.web.status_invite_error') }}";
                            var errorMsg = codeMsg.replace('{id}', recordTypeStr + errorUserId.join(','));
                            layer.open({
                                content: errorMsg,
                                btn: 'OK',
                                shadeClose: false,
                                yes: function(index) {
                                    layer.close(index);
                                }
                            });
                            return false;
                        }
                    }

                    $.each(_this.arr, function(key, val){
                        console.log(val);
                        if (!val) {
                            layer.open({
                                content: "入力内容を確認してから再度お試しください。",
                                skin: 'msg',
                                time: 2
                            });
                            sub = false;
                        }
                    });

                    if (sub) {
                        ajaxApi(_this.saveUrl, _this.arr, function (d) {
                            layer.open({
                                content: d.msg,
                                btn: 'OK',
                                shadeClose: false,
                                yes: function(index) {
                                    layer.close(index);
                                    if (d.status == 200) {
                                        if (_this.check_all_button) {
                                            window.location.reload();
                                        } else {
                                            _this.memo_list();
                                            _this.arr.status_user = 0;
                                            _this.arr.memo = '';
                                        }

                                    }
                                }
                            });
                        });
                    }
                },

                change_status_invite: function () {
                    var _this = this;
                    _this.arr.record_id = _this.sh.record_id;

                    var msg;
                    var errorUserId = [];
                    if (_this.arr.record_id.length > 0) {
                        _this.arr.record_id.forEach(function (item, index) {
                            if (typeof _this.statusArr[item] == "undefined" || _this.statusArr[item] >= 3) {
                                errorUserId.push('[' + _this.accountCodeArr[item] + ']');
                            }
                        });

                        if (errorUserId.length > 0) {
                            var codeMsg = "{{ config('code.alert_msg.web.status_invite_error') }}";
                            var errorMsg = codeMsg.replace('{id}', recordTypeStr + errorUserId.join(','));
                            layer.open({
                                content: errorMsg,
                                btn: 'OK',
                                shadeClose: false,
                                yes: function(index) {
                                    layer.close(index);
                                }
                            });
                            return false;
                        }
                        msg = "{{ config('code.alert_msg.web.status_invite') }}";
                    } else {
                        var subUserId = [];

                        for (i in _this.statusArr) {
                           if(_this.statusArr[i] < 3) {
                               subUserId.push(i);
                           }
                        }
                        _this.arr.record_id = subUserId;
                        msg = "{{ config('code.alert_msg.web.status_invite_all') }}";
                    }
                    _this.arr.status_user = 3;

                    $.each(_this.arr, function(key, val){
                        if (!val) {
                            layer.open({
                                content: "入力内容を確認してから再度お試しください。",
                                skin: 'msg',
                                time: 2
                            });
                            sub = false;
                        }
                    });

                    layer.open({
                        content:msg
                        ,btn: ['はい', 'いいえ']
                        ,yes: function(index){
                            layer.close(index);
                            ajaxApi(_this.saveUrl, _this.arr, function (d) {
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
                        }
                        ,no: function(index){
                            layer.close(index);
                        }
                    });
                },

            },
        });

        $('#popupTrigger').popup();

        $(document).ready(function() {
            $('.company_management_table').dataTable({
                "searching": false,
                "paging": false,
                "lengthChange": false,
                "info": false,
                "columnDefs": [{
                    "targets": [0, 2, 3, 9, 14],
                    "orderable": false
                }],
                "order": [1],
                "language": {
                    "emptyTable": "該当応募者がいません。",
                },
            });
        });

    </script>

    <script type="text/javascript" src="{{asset('/js/jquery.autoTextarea.js')}}"></script>

@endsection