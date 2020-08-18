@extends('layouts.web')
@section('content')
    <script src="{{asset('/js/ajaxfileupload.js')}}"></script>

    <div id="app">
        <div class="main">
            <div class="my_main">
                <div class="my_body">
                    <div class="my_title_line"><a href="javascript:history.back()" class="back_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> 戻る</a></div>

                    <div class="my_application_message">
                        <ul>
                            <li>
                                <div class="msg_tit">応募職種名</div>
                                <div class="msg_info">
                                    <h3><a href="{{ route('job.detail', [$jobInfo['job_id']]) }}">{{ $jobInfo['job_name'] ? $jobInfo['job_name'] : $jobInfo['position'] }}</a></h3>
                                    <template v-if="recordInfo.status_company > 2">
                                        <span class="msg_show_btn" @click="msg_show_btn">メッセージを送る <i class="fa fa-chevron-down fi-arrow-down"></i></span>
                                    </template>
                                    <template v-else>
                                    </template>

                                </div>
                            </li>
                            <li class="fold_item">
                                <div class="msg_tit">件名</div>
                                <div class="msg_info"><input type="text" class="input_text" maxlength="200" v-model="message.title"/></div>
                            </li>
                            <li class="fold_item">
                                <div class="msg_tit">本文</div>
                                <div class="msg_info"><input type="text" class="input_text" maxlength="200" v-model="message.content"/></div>
                            </li>
                            <li class="fold_item">
                                <div class="msg_btn_box">
                                    <input type="button" @click="message_save" value="送信" class="msg_btn" />
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
                                            <div class="msg_info_btn" v-if="item.type==2 && item.status_company == 3">
                                                <template v-if="recordInfo.status_user < 3 || recordInfo.status_user == 4">
                                                    <a class="msg_agree_btn" @click="agree_btn">同意</a>
                                                </template>
                                                <template v-if="recordInfo.status_user < 3">
                                                    <a class="msg_return_btn" @click="return_btn">辞退</a>
                                                </template>
                                                    <a class="msg_agree_btn2" @click="companyinfo_Popup">会社情報を確認</a>
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

        <form id="testForm" action="" method="post" accept-charset="UTF-8" name="testForm">
            <div class="popup" tabindex="-1" role="dialog" data-popup-id="demoPopup" id="agree_btn_pop">
                <div class="popup__container">


                    <div class="popup__content">
                        <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                            <h3>
                                <div class="popup__close" @click="closePop1"><i class="fa fa-times" aria-hidden="true"></i></div>履歴書を作成します

                            </h3>
                            <ul class="upload_main">
                                <template v-if="!experienceInfo.photo">
                                <li><a href="{{ route('web.experiences.my') }}" class="web_resume_link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>WEBで履歴書と職務経歴書をご記入ください。</a></li>
                                <li class="item_title"><span>ご自身で作成された履歴書・職務経歴書(PDF)があればアップロードしてください。</span></li>
                                <li class="my_item_input1">
                                    <div class="my_item_input_file">
                                        <input type="hidden" v-model="status_data.resume_1">
                                        <input class="input_text pdf_file" v-model="showPhoto.resume_1" placeholder="PDFファイル形式のアップロードは、2 mb以下です。">

                                        <input type="button" class="upload_btn" @click="uploadPhoto('resume_1')" value="アップロード" />
                                        <input type="file" name="attachment" class="upload_file my_pdf" id="resume_1" @change="onFileChange"/>
                                    </div>
                                </li>
                                    <li class="my_item_input1">
                                        <div class="my_item_input_file">
                                            <input type="hidden" v-model="status_data.resume_2">
                                            <input class="input_text pdf_file" v-model="showPhoto.resume_2" placeholder="PDFファイル形式のアップロードは、2 mb以下です。">

                                            <input type="button" class="upload_btn" @click="uploadPhoto('resume_2')" value="アップロード" />
                                            <input type="file" name="attachment_1" class="upload_file my_pdf" id="resume_2" @change="onFileChange"/>
                                        </div>
                                    </li>
                                <li class="item_title"><span>参考フォーマットダウンロード：</span></li>
                                <li><a href="/images/resume_1.doc" download="resume_1.doc" class="example_resume_link"><i class="fa fa-download" aria-hidden="true"></i> 履歴書のフォーマット</a></li>
                                    <li><a href="/images/resume_2.doc" download="resume_2.doc" class="example_resume_link"><i class="fa fa-download" aria-hidden="true"></i> 職務経歴書のフォーマット</a></li>
                                </template>
                                <template v-else>

                                    <li class="item_title">
                                        <input type="checkbox" v-mode="status_data.confirm" name="confirm">
                                        <label for="confirmation_checkbox" class="confirmation_checkbox"></label>
                                        <a class="confirmation_link" @click="privacyPopup">WEBで履歴書と職務経歴書をご確認下さい</a>
                                    </li>

                                </template>
                            </ul>
                            <div class="pop_btn">
                                <input type="button" value="キャンセル" @click="closePop1" class="pop_cancel_btn" />
                                <input type="button" value="企業に送信" @click="agree_btn_submit" class="pop_ok_btn" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="popup" tabindex="-1" role="dialog" data-popup-id="companyinfo_Popup" id="companyinfo_Popup">
            <div class="popup__container">


                <div class="popup__content">
                    <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                        <h3>
                            <div class="popup__close" @click="closePop1"><i class="fa fa-times" aria-hidden="true"></i></div>会社情報を確

                        </h3>
                        <div class="companyinfo_box">
                            <ul>
                                <li><span>会社名</span><span><b>{{ $companyInfo['company_name'] }}</b></span></li>
                                @if($companyInfo['company_url'])
                                <li><span>会社ホームページ</span><span><b><a href="{{ $companyInfo['company_url'] }}">{{ $companyInfo['company_url'] }}</a></b></span></li>
                                @endif
                                <li><span>会社住所</span><span><b>{{ config("code.resume.country_city")[$companyInfo['address_id']] }}  {{ $companyInfo['address'] }}</b></span></li>
                            </ul>
                        </div>
                        <div class="pop_btn">
                            <input type="button" value="確定" @click="closePop1" class="pop_ok_btn" />
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="popup" tabindex="-3" role="dialog" data-popup-id="job_summary_example">
            <div class="popup__container">
                <div class="popup__close"><span></span><span></span></div>
                <div class="popup__content">
                    <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                        <h3>職務要約范文 </h3>
                        <h4>Job summary example</h4>
                        <div class="job_summary_content_text">
                            大学卒業後、人材派遣の営業職として、中小～大手クライアント向け提案営業に従事してきました。新規開拓からはじまり既存顧客への実績拡大のための深耕営業も経験しております。現在はマネージャーとして、メンバー・業績マネジメントを行っております。
                            <div class="pop_btn">
                                <input type="button" value="キャンセル" class="pop_cancel_btn" />
                                <input type="button" value="確定" class="pop_ok_btn" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup" tabindex="-3" role="dialog" data-popup-id="privacyPopup" id="privacyPopup">
            <div class="popup__container">

                <div class="popup__content">
                    <div class="privacy_content" role="dialog" data-popup-id="demoPopup">
                        <h3>
                            <div class="popup__close2" @click="closePop1"><i class="fa fa-times" aria-hidden="true"></i></div>WEBで履歴書と職務経歴書をご確認下さい
                        </h3>

                        <div class="privacy_text">
                            <p>メールアドレスから作成される匿名化された
                                (「ハッシュ」とも呼ばれる) 文字列は、あなたが Gravatar サービスを使用中かどうか確認するため同サービスに提供されることがあります。同サービスのプライバシーポリシーは
                                https://automattic.com/privacy/ にあります。コメントが承認されると、プロフィール画像がコメントとともに一般公開されます。</p>
                            <p>このウェブサイトは、ハイナビズ株式会社（以下「当社」）の事業内容等を紹介するサイトです。</p>
                            <p>個人情報保護方針<br>当社は、以下のとおり個人情報保護方針を定め、個人情報保護の仕組みを構築し、全従業員に個人情報保護の重要性の認識と取組みを徹底させることにより、個人情報の保護を推進致します。</p>
                            <p>個人情報の管理<br>当社は、お客さまの個人情報を正確かつ最新の状態に保ち、個人情報への不正アクセス・紛失・破損・改ざん・漏洩などを防止するため、セキュリティシステムの維持・管理体制の整備・社員教育の徹底等の必要な措置を講じ、安全対策を実施し個人情報の厳重な管理を行ないます。</p>
                            <p>個人情報の利用目的<br>本ウェブサイトでは、お客様からのお問い合わせ時に、お名前、e-mailアドレス、電話番号等の個人情報をご登録いただく場合がございますが、これらの個人情報はご提供いただく際の目的以外では利用いたしません。<br>お客さまからお預かりした個人情報は、当社からのご連絡や業務のご案内やご質問に対する回答として、電子メールや資料のご送付に利用いたします。</p>
                            <p>個人情報の第三者への開示・提供の禁止<br>当社は、お客さまよりお預かりした個人情報を適切に管理し、次のいずれかに該当する場合を除き、個人情報を第三者に開示いたしません。<br>・お客さまの同意がある場合<br>・お客さまが希望されるサービスを行なうために当社が業務を委託する業者に対して開示する場合<br>・法令に基づき開示することが必要である場合</p>
                            <p>個人情報の安全対策<br>当社は、個人情報の正確性及び安全性確保のために、セキュリティに万全の対策を講じています。</p>
                            <p>ご本人の照会<br>お客さまがご本人の個人情報の照会・修正・削除などをご希望される場合には、ご本人であることを確認の上、対応させていただきます。</p>
                            <p>法令、規範の遵守と見直し<br>当社は、保有する個人情報に関して適用される日本の法令、その他規範を遵守するとともに、本ポリシーの内容を適宜見直し、その改善に努めます。</p>
                            <p>Google
                                Analyticsの利用について<br>当サイトではホームページの利用状況を把握するためにGoogle Analytics を利用しています。 Google
                                Analytics から提供されるCookie を使用していますが、Google Analytics によって個人を特定する情報は取得していません。</p>
                            <p>Google
                                Analytics の利用により収集されたデータは、Google社のプライバシーポリシーに基づ いて管理されています。Google Analyticsの利用規約・プライバシーポリシーについてはGoogle
                                Analytics
                                のホームページでご確認ください。</p>
                            <p>Google
                                アナリティクス サービス利用規約<br>https://www.google.co.jp/analytics/terms/jp.html<br>Google
                                ポリシーと規約<br>https://policies.google.com/</p>
                            <p>なお、Google
                                Analyticsのサービス利用による損害については、当社は責任を負わないものと します。</p>
                            <div class="pop_btn">
                                <input type="button" value="確定"  @click="closePop1" class="pop_ok_btn2" />
                            </div>
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
                statusAddUrl: "{{ route('web.record.statusAdd') }}",
                pdfUpload: "{{ URL::route('upload.save',['resume']) }}",
                readUrl: "{{ URL::route('web.record.read') }}",
                message: {
                    record_id: 0,
                    title: '',
                    content: '',
                },
                listArr: @json($list),
                recordInfo: @json($recordInfo),
                experienceInfo: @json($experienceInfo),

                status_data: {
                    record_id: 0,
                    status_user: 0,
                    resume_1: '',
                    resume_2: '',
                    confirm: [],
                },

                showPhoto: {
                    resume_1: '',
                    resume_2: '',
                },

                file: {
                    resume_1: '',
                    resume_2: '',
                },

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
                        _this.status_data[name].push(event.currentTarget.value);
                    }
                });

                $("input").on('ifUnchecked',function(event) {
                    var name = event.target.getAttribute('name');
                    if(!event.currentTarget.checked) {
                        _this.status_data[name].splice(_this.status_data[name].indexOf(event.currentTarget.value),1);
                    }
                });

                _this.showPhoto.resume_1 = _this.experienceInfo.resume_1 ? _this.experienceInfo.resume_1 : '';
                _this.showPhoto.resume_2 = _this.experienceInfo.resume_2 ? _this.experienceInfo.resume_2 : '';

                _this.status_data.resume_1 = _this.showPhoto.resume_1;
                _this.status_data.resume_2 = _this.showPhoto.resume_2;

                _this.message.record_id = _this.recordInfo.id;
                _this.status_data.record_id = _this.recordInfo.id;

            },
            methods: {
                msg_show_btn: function (obj) {
                    var _this = this;
                    $(obj.target).toggleClass("open");
                    $(".my_application_message_view").slideToggle();
                    $(".fold_item").toggleClass("on");

                    ajaxApi(_this.readUrl, {'record_id':_this.recordInfo.id}, function (d) {
                        console.log(d);
                    });
                },

                privacyPopup: function (obj) {
                    $("#privacyPopup").addClass("opened");
                },

                companyinfo_Popup: function (obj) {
                    $("#companyinfo_Popup").addClass("opened");
                },

                agree_btn: function (obj) {
                    $("#agree_btn_pop").addClass("opened");
                },

                agree_btn_submit: function (obj) {
                    var _this = this;
                    _this.status_data.status_user = 3;
                    if (_this.experienceInfo.photo) {
                        if (_this.status_data.confirm.length <= 0) {
                            layer.open({
                                content: "管理画面でご登録された履歴書・職務経歴書をご確認下さい。",
                                skin: 'msg',
                                time: 2
                            });
                            return false;
                        }
                    } else {
                        if (!_this.status_data.resume_1 || !_this.status_data.resume_2) {
                            layer.open({
                                content: "ご自身で作成された履歴書(PDF)と職務経歴書(PDF)があればアップロードしてください。",
                                skin: 'msg',
                                time: 2
                            });
                            return false;
                        }
                    }

                    _this.message_save_status();

                    $("#agree_btn_pop").removeClass("opened");
                },

                onFileChange: function(e) {
                    var _this = this;
                    var files = e.target.files || e.dataTransfer.files;

                    var fileElementId = e.target.id;

                    if (typeof  files[0] == 'undefined') {
                        this.file[fileElementId] = null;
                        return ;
                    }

                    _this.showPhoto[fileElementId] = $("#" + fileElementId).val();
                    _this.file[fileElementId] = e;

                    $(document).off('change','#' + fileElementId).on('change','#' + fileElementId, function(e){
                        _this.onFileChange(e);
                    });
                },

                uploadPhoto: function (id) {
                    var _this = this;
                    if (!_this.file[id]) {
                        return ;
                    }
                    var fileElementId = this.file[id].target.id;
                    $.ajaxFileUpload({
                        url: _this.pdfUpload,
                        secureuri: false,
                        fileElementId: fileElementId, //文件上传域的ID，这里是input的ID，而不是img的
                        dataType: 'json', //返回值类型 一般设置为json
                        type: 'post',
                        async : false,
                        success: function (data) {
                            layer.open({
                                content: data.msg,
                                skin: 'msg',
                                time: 2
                            });

                            if (data.status==200){
                                _this.status_data[fileElementId] = data.data.file_path;
                                _this.showPhoto[fileElementId] = data.data.file_path;
                            } else {
                                _this.showPhoto[fileElementId] = '';
                                _this.file[fileElementId] = '';
                            }
                            _this.file[id] = null;
                            return false;
                        },
                        error: function (err){
                            console.log(err);
                        }
                    });
                },

                return_btn: function (obj) {
                    var _this = this;
                    layer.open({
                        content: '本当に辞退しますか？'
                        ,btn: ['はい', 'いいえ']
                        ,yes: function(index){
                            _this.status_data.status_user = 4;
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

                closePop1: function (obj) {
                    $(obj.target).parents('.popup').removeClass('opened');
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

                    // if (!(_this.recordInfo.read_user && _this.recordInfo.status_user >= 3)) {
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
                                        _this.listArr = d.list;
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