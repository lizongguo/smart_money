@extends('layouts.company')
@section('content')

    <div id="app">
        <div class="main">
            <div class="company_main company_main_list">
                <div class="company_body">
                    <div class="company_item_info">
                        <span>募集ID：<b>{{ $jobInfo['job_code'] ? $jobInfo['job_code'] : $jobInfo['job_id'] }}</b></span>
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
                        <span>掲載期間：<b>@if($jobInfo['job_period_type'] == 1)
                                    {{ $jobInfo['job_period_start'] }}  -  {{ $jobInfo['job_period_end'] }}
                                @elseif($jobInfo['job_period_type'] == 2)
                                    無期限
                                @else
                                    掲載中止
                                @endif</b></span>
                    </div>

                    <form id="testForm" method="get">
                        <div class="company_list_title">
                            <h3>スカウト管理
                                <span>
                                    <a href="javascript:void(0)" @click="change_status" class="change_btn">スカウト</a>
                                </span>
                            </h3>
                            <div class="search_main">
                                <div class="search_box">
                                    <div class="search_input">
                                        <input type="text" readonly class="search_text" name="search_text_str" v-model="sh.search_text_str"/>
                                        <a class="search_clear"><i class="fa fa-times"></i></a>
                                        <span class="search_choose" id="popupTrigger" data-popup-target="searchPopup">選択 <i class="fa fa-chevron-down fi-arrow-down"></i></span></div>
                                </div>
                                <div class="search_btn_box">
                                    <input type="button" value="求人検索" @click="search_list" class="search_btn" />
                                </div>
                            </div>
                        </div>

                        <div class="popup" tabindex="-1" role="dialog" data-popup-id="searchPopup">
                            <div class="popup__container">

                                <input type="hidden" name="sex_arr">
                                <input type="hidden" name="address_arr">
                                <input type="hidden" name="employment_status_arr">
                                <input type="hidden" name="science_arts_arr">
                                <input type="hidden" name="final_education_arr">

                                <div class="popup__content">
                                    <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                                        <h3>
                                            <div class="popup__close"><i class="fa fa-times" aria-hidden="true"></i></div>
                                            <div class="storage_clear" @click="clear_button"><i class="fa fa-refresh" aria-hidden="true"></i>リセット</div>選択
                                        </h3>
                                        <ul>
                                            <li class="item_title"><span>日本語</span></li>
                                            <li>
                                                <div class="select">
                                                    <select v-model="sh.jp_level_2" name="jp_level_2">
                                                        <option value="" selected>選択する</option>
                                                        <option v-for="(item, k) in config_jp_level_2" :value="k"><{ item }></option>
                                                    </select>
                                                </div>
                                            </li>
                                            <li class="resume_item_title">国籍(地域)<span>＊</span></li>
                                            <li class="country_select">
                                                <div class="select">
                                                    <select v-model="sh.nationality_id" name="nationality_id">
                                                        <option value="" selected>選択する</option>
                                                        <option v-for="(item, k) in config_nationality_id" :value="k"><{ item }></option>
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
                                <th class="">ID</th>
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

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $k => $v)
                                <tr>
                                    <td>
                                        <input type="checkbox" v-model="sh.record_id" name="record_id" value="{{ $v['user_id'] }}" class="check_box">
                                    </td>
                                    <td>
                                        {{ $v['account_code'] ? $v['account_code'] : $v['user_id'] }}
                                    </td>
                                    <td>
                                        <a href="{{ route('company.record.resume_info', [$v['user_id']]) }}">未開示</a>
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
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $list->appends(Request::all())->links('company.common.pagination') }}
                        <div class="clear"></div>
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
                sh: @json($data),

                statusAddUrl: "{{ route('company.scout.selectUser') }}",

                search_text_arr: [],


                config_age: [],
                config_jp_level_2: @json(config("code.resume.jp_level_2")),
                config_nationality_id: @json(config("code.resume.nationality")),
                config_sex: @json(config("code.resume.sex")),
                config_address: @json(config("code.resume.address")),
                config_employment_status: @json(config("code.resume.employment_status")),
                config_science_arts: @json(config("code.resume.science_arts")),
                config_final_education: @json(config("code.resume.final_education")),

                config_record: @json(config("code.record")),

                status_by_id: [],
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
                _this.sh.job_id = "{{ $jobInfo['job_id'] }}";

            },
            methods: {
                search_text: function () {
                    var _this = this;
                    $("input[name='sex_arr']").val(_this.sh.sex.join(','));
                    $("input[name='address_arr']").val(_this.sh.address.join(','));
                    $("input[name='employment_status_arr']").val(_this.sh.employment_status.join(','));
                    $("input[name='science_arts_arr']").val(_this.sh.science_arts.join(','));
                    $("input[name='final_education_arr']").val(_this.sh.final_education.join(','));


                    if (_this.sh.jp_level_2) {
                        _this.search_text_arr.push( _this.config_jp_level_2[_this.sh.jp_level_2]);
                    }

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

                    layer.open({
                        content: 'スカウト?'
                        ,btn: ['はい', 'いいえ']
                        ,yes: function(index){
                            ajaxApi(_this.statusAddUrl, _this.sh, function (d) {
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
                            layer.close(index);
                        }
                        ,no: function(index){
                            layer.close(index);
                        }
                    });

                    $("#optionPopup").addClass("opened");
                },

                checkbox_all: function () {
                    var _this = this;
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
                    "targets": [0, 1, 2, 8],
                    "orderable": false
                }],
                "order": [],
                "language": {
                    "emptyTable": "該当応募者がいません。",
                },
            });
        });

    </script>

@endsection