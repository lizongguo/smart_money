@extends('layouts.web')
@section('content')

    <div id="app">
        <div class="main">
            <div class="my_body">
                <div class="my_title_line"><a href="javascript:history.back()" class="back_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> 戻る</a></div>
                <div class="my_application_message">
                    <ul class="candidate_info_item">
                        <li><span>応募日：<b><{ recordInfo.job_record_created_at }></b></span><span>応募者ID：<b><{ recordInfo.account_code }></b></span><span>氏名：<b><{ recordInfo.user_id }></b></span></li>
                        <li><span>国籍：<b><{ recordInfo.nationality_id }></b></span><span>性別：<b><{ recordInfo.sex }></b></span><span>年齢：<b><{ recordInfo.birthday }></b></span></li>
                        <li><span>現住所：<b><{ recordInfo.address }></b></span></li>
                        <li><span>日本語：<b><{ recordInfo.jp_level }></b></span><span>就業状況：<b><{ recordInfo.employment_status }>（<{ recordInfo.employment_status_extra }>）</b></span><span>文理区分：<b><{ recordInfo.science_arts }></b></span></li>
                        <li><span>最終学歴：<b><{ recordInfo.final_education }>[<{ recordInfo.university }>][<{ recordInfo.major }>]</b></span><span>選考状況：<b><{ recordInfo.status_company_str }></b></span></li>
                    </ul>
                    <ul>
                        <li>
                            <div class="msg_tit">応募職種名</div>
                            <div class="msg_info">
                                <h3><a :href="'/jobdetail/'+jobInfo.job_id"><template v-if="jobInfo.job_name"><{recordInfo.status_company}><{ jobInfo.job_name }></template><template v-else ><{ jobInfo.position }></template></a></h3>

                                <template v-if="recordInfo.status_company > 2">
                                    <span class="msg_show_btn" @click="msg_show_btn">メッセージを送る <i class="fa fa-chevron-down fi-arrow-down"></i></span>
                                </template>
                                <template v-else>
                                </template>
                            </div>
                        </li>
                        <li class="fold_item">
                            <div class="msg_tit">件名</div>
                            <div class="msg_info"><input type="text" maxlength="200" v-model="message.title" class="input_text" /></div>
                        </li>
                        <li class="fold_item">
                            <div class="msg_tit">本文</div>
                            <div class="msg_info"><input type="text" maxlength="200" v-model="message.content" class="input_text"  /></div>
                        </li>
                        <li class="fold_item">
                            <div class="msg_btn_box">
                                <input type="button" @click="message_save" value="送信" class="msg_button" />
                            </div>
                        </li>
                        <div class="clear"></div>
                    </ul>

                </div>
                <div class="my_application_message_view">
                    <div class="windows_body">
                        <div class="office_text" style="height: 100%; overflow: hidden;">
                            <ul class="content" id="chatbox">
                                <li v-for="(item) in listArr" :class="{'enterprise' : item.type==2, 'me' : item.type==1}">
                                    <div class="user_img"><img src="/images/user.png"><template v-if="item.type==2">企業</template><template v-else>応募者</template></div>
                                    <div class="msg_info_box">
                                        <div class="msg_info_content">
                                            <{ item.title }>
                                            <br/>
                                            <{ item.content }>
                                        </div>
                                        <p class="msg_time"><{ item.created_at }></p>
                                        <div class="msg_info_btn" v-if="item.type==1 && item.status_user == 3">
                                            <template v-if="recordInfo.status_company < 3">
                                                <a class="msg_agree_btn" @click="return_btn(3)">合格</a>
                                                <a class="msg_return_btn" @click="return_btn(4)">不合格</a>
                                            </template>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
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
                statusAddUrl: "{{ route('company.record.statusAdd') }}",
                readUrl: "{{ URL::route('company.record.read') }}",

                listArr: [],
                recordInfo: [],
                jobInfo: [],

                message: {
                    record_id: "{{ $record_id }}",
                    title: '',
                    content: '',
                },

                status_data: {
                    record_id: "{{ $record_id }}",
                    status_company: 0,
                },

            },
            mounted: function () {
                var _this = this;
                _this.message_list();

            },
            methods: {
                message_list: function () {
                    var _this = this;
                    ajaxApi(_this.listUrl, _this.message, function (d) {
                        _this.listArr = d.list;
                        _this.recordInfo = d.recordInfo;
                        _this.jobInfo = d.jobInfo;
                    });
                },

                return_btn: function (status) {
                    var _this = this;
                    if (status == 3) {
                        var msg = '書類選考には合格ですか？';
                    } else {
                        var msg = '書類選考には不合格ですか？';
                    }

                    layer.open({
                        content: msg
                        ,btn: ['はい', 'いいえ']
                        ,yes: function(index){
                            _this.status_data.status_company = status;
                            _this.message_save_status();
                            layer.close(index);
                        }
                        ,no: function(index){
                            layer.close(index);
                        }
                    });
                },

                message_save_status: function () {
                    var _this = this;
                    ajaxApi(_this.statusAddUrl, _this.status_data, function (d) {
                        layer.open({
                            content: d.msg,
                            btn: 'OK',
                            shadeClose: false,
                            yes: function(index) {
                                layer.close(index);
                                if (d.status == 200) {
                                    _this.listArr = d.list;
                                    _this.recordInfo = d.recordInfo;
                                }
                            }
                        });
                    });
                },

                msg_show_btn: function (obj) {
                    var _this = this;

                    $(obj.target).toggleClass("open");
                    $(".my_application_message_view").slideToggle();
                    $(".fold_item").toggleClass("on");

                    ajaxApi(_this.readUrl, {'record_id':_this.recordInfo.id}, function (d) {
                        console.log(d);
                    });
                },

                message_save: function () {
                    var _this = this;
                    
                    if (_this.recordInfo.finish_status == 1) {
                        layer.open({
                            content: "今回の応募にメッセージのやりとりは終了しました。",
                            skin: 'msg',
                            time: 2
                        });
                        return false;
                    }

                    // if (!(_this.recordInfo.read_company && _this.recordInfo.status_company >= 3)) {
                    //     layer.open({
                    //         content: "相手からのメッセージをお待ち下さい。",
                    //         skin: 'msg',
                    //         time: 2
                    //     });
                    //     return false;
                    // }

                    var sub = true;
                    $.each(_this.message, function(key, val){
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
                        ajaxApi(_this.statusAddUrl, _this.message, function (d) {
                            layer.open({
                                content: d.msg,
                                btn: 'OK',
                                shadeClose: false,
                                yes: function(index) {
                                    layer.close(index);
                                    if (d.status == 200) {
                                        _this.message_list();
                                        _this.message.title = "";
                                        _this.message.content = "";
                                    }
                                }
                            });
                        });
                    }
                },

            }
        });

    </script>
@endsection