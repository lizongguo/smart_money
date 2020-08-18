@extends('layouts.web')
@section('content')
    <div class="main">

        <form id="testForm" action="{{ route('web.index.agent') }}" method="post" accept-charset="UTF-8" name="testForm">
            <div class="post_info_body agent_info_body">
                <h3>人材紹介事業の提携に関する問い合わせ（エージェント様向け）</h3>
                <div class="post_info_content">
                    <ul>
                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">所在地（国・地域）<span>＊</span></li>
                                <li class="item_input">
                                    <div class="select">
                                        <select name="nationality_id" id="select4" onchange="select4Change()" required>
                                            <option value="" selected>選択する</option>
                                            @foreach(config("code.resume.nationality_agent") as $k => $v)
                                                @if($k > 0)
                                                    <option @if($data['fileds'] == $k) selected @endif value="{{$k}}">{{$v}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title"></li>
                                <li class="item_input"><input type="text" maxlength="100" class="input_text agent_other" value="" placeholder="その他" name="nationality" data-max="100" required />
                                </li>
                            </ul>
                        </li>

                        <li class="item_title">会社名<span>＊</span></li>
                        <li class="item_input">
                            <input type="text" class="input_text" value="" placeholder="50文字以内ご記入ください。" maxlength="50" name="agent_name" data-max="100" required />
                        </li>
                        <li class="item_title">会社住所<span>＊</span></li>
                        <li class="item_input">
                            <input type="text" class="input_text" value="" placeholder="100文字以内ご記入ください。" maxlength="100" name="agent_address" data-max="100" required />
                        </li>
                        <li class="item_title">会社ホームページ</li>
                        <li class="item_input">
                            <input type="url" class="input_text" value="" maxlength="100"  name="url" placeholder="http://www.xxx.com" data-max="100" />
                        </li>
                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">担当者名<span>＊</span></li>
                                <li class="item_input"><input type="text" placeholder="50文字以内ご記入ください。" class="input_text" maxlength="50" value="" name="principal_name" data-max="100" required />
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title">電話番号<span>＊</span></li>
                                <li class="item_input"><input type="tel" class="input_text" maxlength="20" value="" name="cell_phone" data-max="100" required />
                                </li>
                            </ul>
                        </li>


                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">Eメールアドレス<span>＊</span></li>
                                <li class="item_input"><input type="email" maxlength="200" class="input_text agent_email" value="" placeholder="example@findjapanjob.com" name="email" data-max="100" required />
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title">提携希望<span>＊</span></li>
                                <li class="item_input">
                                    @foreach(config("code.agent.type") as $k => $v)
                                        @if ($k > 0)
                                            <span><input type="checkbox" class="checkbox_type" name="checkbox_type" value="{{$k}}" @if(in_array($k, $target)) checked @endif><label>{{$v}}</label></span>
                                        @endif
                                    @endforeach
                                    <input type="hidden" name="type">
                                </li>
                            </ul>
                        </li>

                        <li class="item_title">問い合わせ内容</li>
                        <li><textarea class="text_area" id="consulting_content"  maxlength="2000" name="content" placeholder="日本語でご記入ください。"></textarea></li>
                        <!-- <li class="privacy_item">

                            <p><input type="checkbox" id="con_checkbox7" value="7" name="con_checkbox7" required> <a href="javascript:void(0)" id="popupTrigger3" data-popup-target="privacyPopup">プライバシーポリシー</a>が適用されます</p>

                        </li> -->
                        <li class="privacy_item">
                            <p>情報の取扱いについては「<a href="#" id="popupTrigger3" data-popup-target="privacyPopup">ライバシーポリシー</a>」のページをご覧いただき、
                                その内容に同意する方のみ「送信」を押してください</p>
                        </li>
                        <div class="step_pages_end"><input type="submit" class="resume_ok_btn" value="送信"></div>
                        <div class="popup" tabindex="-3" role="dialog" data-popup-id="privacyPopup">
                            <div class="popup__container">
                                <div class="popup__content">
                                    <div class="privacy_content" role="dialog" data-popup-id="demoPopup">
                                        <h3>個人情報保護方針</h3>
                                        <h4>PRIVACY POLICY</h4>
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
                                                <input type="button" value="確定" class="pop_ok_btn" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </form>
    </div>

    <script>

        $('#popupTrigger3').popup();

        $("#testForm").html5Validate(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
            });
            $("body").mLoading({
                text:"送信中...",//加载文字
                icon:"/images/loading.gif",//加载图标
                mask:true//是否显示遮罩
            });
            $.ajax({
                type: "post",
                url: $("#testForm").attr("action"),
                dataType: "json",
                data: $("#testForm").serialize(),
                success: function(content){
                    $("body").mLoading("hide");
                    layer.open({
                        content: content.msg,
                        btn: 'OK',
                        shadeClose: false,
                        yes: function(index) {
                            layer.close(index);
                            if (content.status == 200) {
                                window.location.href = "{{ route("web.index.index") }}";
                            }
                        }
                    });
                },
                error: function (err){
                    console.log(err);
                }
            });

        }, {
            validate: function() {
                var type_arr = [];
                $("input[name='checkbox_type']:checked").each(function () {
                    type_arr.push($(this).val());
                });

                if (type_arr.length > 0) {
                    $("input[name='type']").val(type_arr.join(","));
                } else {
                    $("input[name='checkbox_type']").testRemind("ご選択ください");
                    $("input[name='checkbox_type']").focus();
                    return false;
                }

                return true;
            }
        });

        $(document).ready(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
            });

            select4Change();
        });

        function select4Change() {
            var text = $("#select4 option:selected").text();
            if (text == "その他") {
                $("input[name='nationality']").show();
                $("input[name='nationality']").attr("required", "true");
            } else {
                $("input[name='nationality']").removeAttr("required");
                $("input[name='nationality']").hide();
            }
        }

    </script>

    <script type="text/javascript" src="{{asset('/js/jquery.autoTextarea.js')}}"></script>

@endsection