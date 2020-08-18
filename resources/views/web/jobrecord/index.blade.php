@extends('layouts.web')
@section('content')
    <div id="app">
        <div class="main">
            <div class="my_main">
                <div class="my_body">
                    @include('web.common.user_menu')

                    <div class="my_my_application_main">
                        <table class="my_my_application_table">
                            <tr class="my_application_title">
                                <th class="my_application_name_item">@if($type == "record")応募職種名@else スカウト職種名 @endif</th>
                                <th class="my_application_state_item">選考状況</th>
                                <th class="my_application_message_item">メッセージ</th>
                            </tr>
                            @if(isset($list) and count($list) > 0)
                                @foreach($list as $k => $v)
                                    <tr @if($v['read_user'])class="active"@endif>
                                        <td class="my_application_name_text">
                                            <div class="text_info"><a href="{{ route("job.detail", [$v['job_id']]) }}">{!! $v['job_name'] ? $v['job_name'] : $v['position'] !!}</a></div>
                                        </td>
                                        <td class="my_application_name_text">
                                            <p><a data-popup-target="demoPopup" @click="status_user_click({{$v['id']}})" class="popupTrigger state_btn">{{ $recordInfo['status_user'][$v['status_user']] }}</a></p>
                                            <p align="center"><span>@if($v['status_user_time']){{ date("Y-m-d H:i", strtotime($v['status_user_time'])) }}@endif</span></p>
                                        </td>
                                        <td class="my_application_name_text">
                                            <p><a href="{{ route('web.record.statusList', [$v['id']]) }}" class="message_btn">@if($v['read_user'])未読@else 既読@endif</a></p>
                                            <p align="center"><span>@if($v['read_user_time']){{ date("Y-m-d H:i", strtotime($v['read_user_time'])) }}@endif</span></p>
                                        </td>
                                    </tr>
                                @endforeach
                            @else

                            @endif
                        </table>
                        {{ $list->appends(Request::all())->links('web.common.pagination') }}
                        <div class="clear"></div>
                    </div>

                </div>

            </div>
        </div>

        <form id="testForm" action="" method="post" accept-charset="UTF-8" name="testForm">
            <div class="popup" tabindex="-1" role="dialog" data-popup-id="demoPopup" id="demoPopup">
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
                                            <option v-for="(item, k) in statusList" :value="k"><{ item }></option>
                                        </select>
                                    </div>
                                </li>
                                </template>

                                <li class="item_title"><span>メモ</span></li>
                                <li><textarea class="text_area" v-model="arr.memo"></textarea></li>
                            </ul>
                            <div class="pop_btn">
                                <template v-if="!buttonStatus">
                                    <input type="button" value="キャンセル" class="pop_cancel_btn" @click="closePop1" />
                                    <input type="button" value="登録" class="pop_ok_btn" @click="memo_save"/>
                                </template>

                                <template v-else>
                                    <input type="button" v-for="(item, k) in statusList" :value="item" class="pop_ok_btn" @click="statusButton(k)" />
                                </template>
                            </div>

                            <div class="state_records">
                                <li class="line_title">
                                    <hr>
                                    <p><span>履歴</span></p>
                                </li>
                                <ul>
                                    <li v-for="(item, i) in listArr">
                                        <p class="state_records_date"><span class="state_records_date_item"><{item.created_at}></span><span class="state_records_text"><{recordInfo.status_user[item.status_user]}></span></p>
                                        <p class="state_records_info"><span class="state_records_info_tit">メモ：</span><span class="state_records_info_text"><{ item.memo }></span></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <script>
        $('.popupTrigger').popup();

        var app = new Vue({
            delimiters: ['<{', '}>'],
            el: '#app',
            data: {
                arr: {
                    record_id: 0,
                    status_user: 0,
                    memo: '',
                },
                saveUrl: "{{ route('web.record.memoAdd') }}",
                listUrl: "{{ route('web.record.memoList') }}",
                listArr: [],
                recordInfo: [],

                statusList: [],
                buttonStatus: 0,
            },
            mounted: function () {
                var _this = this;
            },
            methods: {
                status_user_click: function (id) {
                    var _this = this;
                    _this.arr.record_id = id;
                    _this.memo_list();

                    $("#demoPopup").addClass("opened");
                },

                memo_list: function () {
                    var _this = this;
                    ajaxApi(_this.listUrl, _this.arr, function (d) {
                        _this.listArr = d.list;
                        _this.recordInfo = d.recordInfo;
                        _this.statusList = d.statusList;
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
                    if (_this.statusList.length < 1) {
                        layer.open({
                            content: "変更できる選考状況がありません。",
                            skin: 'msg',
                            time: 2
                        });
                        return false;
                    }
                    var sub = true;
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

                    if (sub) {
                        ajaxApi(_this.saveUrl, _this.arr, function (d) {
                            layer.open({
                                content: d.msg,
                                btn: 'OK',
                                shadeClose: false,
                                yes: function(index) {
                                    layer.close(index);
                                    if (d.status == 200) {
                                        _this.memo_list();
                                        _this.arr.status_user = 0;
                                        _this.arr.memo = '';
                                    }
                                }
                            });
                        });
                    }
                },

                closePop1: function (obj) {
                    $(obj.target).parents('.popup').removeClass('opened');
                },
            },
        });

    </script>

    <script type="text/javascript" src="{{asset('/js/jquery.autoTextarea.js')}}"></script>

@endsection