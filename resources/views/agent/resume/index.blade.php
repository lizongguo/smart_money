@extends('layouts.agent')
@section('content')

    <div id="app">
        <div class="main">
            <div class="agent_login_main">
                <h4 class="agent_name_title">
                    <div class="agent_name_l">
                        <span class="c_m_add_btn"><a href="{{ route('agent.resume.input') }}" data-popup-target="agentinfoPopup"><i class="fa fa-plus" aria-hidden="true"></i> 求職者簡単履歴登録</a></span>
                        登録者一覧
                    </div>
                    <div class="agent_name_r"><span>エージェントID：<b>{{ $agentInfo['account_code'] }}</b></span><span>エージェント会社名：<b>{{ $agentInfo['agent_name'] }}</b></span></div>
                </h4>

                <div class="company_management_main">
                    <table class="company_management_table agent_login_table">
                        <thead>
                        <tr class="company_management_title">
                            <th>求職者ID</th>
                            <th>システム管理ID</th>
                            <th class="nosort">氏名</th>
                            {{--<th class="nosort">国籍</th>--}}
                            <th class="nosort">性別</th>
                            <th class="nosort">年齢</th>
                            <th class="nosort">現住所</th>
                            <th class="nosort">日本語</th>
                            <th class="nosort">文理</th>
                            <th class="nosort">就業状況</th>
                            <th class="nosort">登録日</th>
                            <th>有効期限</th>
                            <th class="nosort">状況</th>
                            <th>求人</th>
                            {{--<th>内定会社名</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $date_over = time() > strtotime($v['agent_time'] . '+1 year') ? '1' : '0';
                        @endphp
                        @foreach($list as $k => $v)
                            <tr @if($date_over) class="date_over" @endif>
                                <td>
                                    <a href="{{ route('agent.resume.user_info',[$v['user_id']]) }}"><span class="qiuren_num">{{ $v['account_code'] ? $v['account_code'] : $v['user_id'] }}</span></a>
                                </td>
                                <td>
                                    {{ $v['agent_account_code'] }}
                                </td>
                                <td>
                                    {{ $v['name'] }}
                                </td>
                                {{--<td>--}}
                                    {{--{{ $v['nationality_id'] }}--}}
                                {{--</td>--}}
                                <td>
                                    {{ $v['sex'] }}
                                </td>
                                <td class="c_m_date_text">
                                    {{ $v['birthday'] }}
                                </td>
                                <td>
                                    {{ $v['address'] }}
                                </td>
                                <td>
                                    {{ $v['jp_level'] }}
                                </td>
                                <td>
                                    {{ $v['science_arts'] }}
                                </td>
                                <td>
                                    {{ $v['employment_status'] }}
                                </td>
                                <td>
                                    {{ date("Y-m-d", strtotime($v['agent_time'])) }}
                                </td>
                                <td @if($date_over) class="deadline" @endif>
                                    {{ date("Y-m-d", strtotime($v['agent_time'] . '+1 year') ) }}
                                </td>
                                <td>
                                    <div class="option_select">
                                        @if ($v['agent_status'] == 4)
                                            {{ config('code.agent.agent_status')[4] }}
                                        @else
                                            <select name="saveStatus" @change="saveStatus($event, {{$v['user_id']}})">
                                                @foreach(config('code.agent.agent_status') as $key => $val)
                                                    @if ($key < 4)
                                                    <option @if($v['agent_status'] == $key) selected @endif value="{{ $key }}">{{ $val }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($v['job_count'] > 0)
                                    <span @click="status_user_click({{$v['user_id']}})" class="qiuren_num">{{ $v['job_count'] }}</span>
                                    @else
                                        {{ $v['job_count'] }}
                                    @endif
                                </td>

                                {{--<td class="nosort msg_btn_list">--}}
                                    {{--@if($v['agent_status'] >= 3)--}}
                                        {{--<p><input type="text" class="agent_company_name input_text" @if($v['agent_status'] == 4) readonly @endif value="{{ $v['agent_company_name'] }}"></p>--}}
                                    {{--@endif--}}
                                        {{--@if($v['agent_status'] == 4)--}}
                                            {{--<p class="request_date"><span class="ok_text"><i class="fa fa-check" aria-hidden="true"></i>  送信済</span> {{ substr($v['agent_request_time'], 0 ,16) }}</p>--}}
                                        {{--@endif--}}
                                    {{--@if($v['agent_status'] == 3)--}}
                                        {{--<div @click="msg_btn_click($event,'{{ $v['account_code'] ? $v['account_code'] : $v['user_id'] }}', '{{$v['name']}}', {{$v['user_id']}})" class="msg_btn">請求</div>--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $list->appends(Request::all())->links('agent.common.pagination') }}
            </div>
        </div>

        <div class="popup" tabindex="-1" role="dialog" data-popup-id="qiurenPopup" id="optionPopup">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                        <h3>
                            <div class="popup__close" @click="closePop1"><i class="fa fa-times" aria-hidden="true"></i></div>求人
                        </h3>
                        <br/>
                        <div class="company_management_main">
                            <table class="company_management_table">
                                <tr class="company_management_title">
                                    <th class="c_m_id">募集ID</th>
                                    <th class="c_m_name">募集職種名</th>
                                    <th class="c_m_state">状況</th>
                                    <th class="c_m_date">时间</th>
                                </tr>
                                <tr v-for="(item, i) in memoListArr">
                                    <td>
                                        <{item.account_code}>
                                    </td>
                                    <td>
                                        <a href="{{ route('job.detail',['']) }}"><{item.job_name}></a>
                                    </td>
                                    <td>
                                        <{status_user[item.status_user]}>
                                    </td>
                                    <td>
                                        <{item.job_record_created_at.substring(0, 10)}>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="popup" tabindex="-1" role="dialog" data-popup-id="msgPopup" id="msg_content">
            <div class="popup__container">


                <div class="popup__content">
                    <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                        <h3>
                            <div class="popup__close" @click="closePop1"><i class="fa fa-times" aria-hidden="true"></i></div><{msg.name}>請求
                        </h3>
                        <br/>
                        <li>
						<textarea class="text_area agent_text_area" id="text_area1" name="text_area1">
ハイナビズ株式会社

お世話になっております。

この度、弊社より紹介させていただきました求職者「<{msg.name}>」（求職者ID:<{msg.code}>） は<{msg.agent_company_name}>の内定をもらいましたので、委託手数料を請求させていただきます。

何卒よろしくお願い申し上げます。

{{ $agentInfo['agent_name'] }}
		</textarea></li>
                        </ul>
                        <div class="pop_btn2">

                            <input type="button" @click="send_msg" value="送信" class="pop_ok_btn" />
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>

        {{--var login = "{{ $agentInfo['principal_name'] }}";--}}
        {{--if (!login) {--}}
            {{--layer.open({--}}
                {{--content: "{{ config('code.alert_msg.web.agent_account_info') }}",--}}
                {{--btn: 'OK',--}}
                {{--shadeClose: false,--}}
                {{--yes: function(index) {--}}
                    {{--layer.close(index);--}}
                    {{--window.location.href = "{{ route('agent.index.info') }}";--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });

        var app = new Vue({
            delimiters: ['<{', '}>'],
            el: '#app',
            data: {
                saveUrl: "{{ route('agent.resume.saveStatus') }}",
                jobListUrl: "{{ route('agent.resume.jobList') }}",
                sendMailUrl: "{{ route('agent.resume.sendMail') }}",
                arr: {
                    agent_status: '0',
                    user_id: '0',
                },
                msg: {
                    code: '',
                    name: '',
                    agent_company_name: '',
                    content: '',
                    user_id: '',
                    agent_name: "{{ $agentInfo['agent_name'] }}",
                },
                status_by_id: @json(config('code.agent.agent_status')),
                status_user: @json(config('code.record.status_user')),
                memoListArr: [],
            },
            mounted: function () {

            },
            methods: {
                saveStatus: function (event, id) {
                    //console.log($(event.target).parents('tr').find('.msg_btn_list').html());
                    var _this = this;
                    _this.arr.user_id = id;
                    _this.arr.agent_status = event.target.value;

                    ajaxApi(_this.saveUrl, _this.arr, function (d) {
                        window.location.reload();
                    });
                },

                status_user_click: function (id) {
                    var _this = this;
                    _this.arr.user_id = id;
                    _this.job_list();
                    $("#optionPopup").addClass("opened");
                },

                msg_btn_click: function (event, code, name, user_id) {
                    var agent_company_name = $(event.target).parents('td').find('.agent_company_name').val();
                    if (!agent_company_name) {
                        {{--layer.open({--}}
                            {{--content: "{{ config('code.alert_msg.web.input_company_name') }}",--}}
                            {{--btn: 'OK',--}}
                            {{--shadeClose: false,--}}
                            {{--yes: function(index) {--}}
                                {{--layer.close(index);--}}
                            {{--}--}}
                        {{--});--}}
                        $(event.target).parents('td').find('.agent_company_name').focus();
                        $(event.target).parents('td').find('.agent_company_name').testRemind("{{ config('code.alert_msg.web.input_company_name') }}");

                        return false;
                    }

                    var _this = this;
                    _this.msg.code = code;
                    _this.msg.name = name;
                    _this.msg.agent_company_name = agent_company_name;
                    _this.msg.user_id = user_id;
                    $("#msg_content").addClass("opened");
                },

                job_list: function () {
                    var _this = this;
                    ajaxApi(_this.jobListUrl, _this.arr, function (d) {
                        _this.memoListArr = d.list;
                    });
                },

                send_msg: function () {
                    var _this = this;
                    var content = $("#text_area1").val();
                    if (!content) {
                        $("#text_area1").testRemind("ご記入ください");
                        $("#text_area1").focus();
                        return false;
                    }
                    _this.msg.content = content;
                    $("body").mLoading({
                        text:"送信中...",//加载文字
                        icon:"/images/loading.gif",//加载图标
                        mask:true//是否显示遮罩
                    });
                    ajaxApi(_this.sendMailUrl, _this.msg, function (d) {
                        $("body").mLoading("hide");
                        layer.open({
                            content: d.msg,
                            btn: 'OK',
                            shadeClose: false,
                            yes: function(index) {
                                layer.close(index);
                                window.location.reload();
                            }
                        });
                    });
                },

                closePop1: function (obj) {
                    var _this = this;
                    _this.arr.status_user = '0';
                    _this.arr.memo = '';
                    $(obj.target).parents('.popup').removeClass('opened');
                },
            },
        });

        $(document).ready(function() {
            $('.agent_login_table').dataTable({
                "searching": false,
                "paging": false,
                "lengthChange": false,
                "info": false,
                "columnDefs": [{
                    "targets": [2,3,4,5,6,7,8,9,11],
                    "orderable": false
                }],
                "order": [0],
                "language": {
                    "emptyTable": "該当登録者がいません。",
                },
            });
        });

    </script>

@endsection