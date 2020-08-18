@extends('layouts.agent')
@section('content')
    <div class="main">
        <div class="logo_box">
            <img src="/images/logo.png" />
            <p style="font-size: 26px;color: #000;font-weight: 600">求職者簡単履歴登録</p>
        </div>
        <form id="resume_add" action="" method="post" accept-charset="UTF-8" name="resume_add">
            {{csrf_field()}}
            <div class="resume_body">
                <h3>&nbsp;<div class="agent_name" style="text-align: right;">登録代行：<b>{{ $agentInfo['agent_name'] }}</b></div></h3>
                <div class="resume_content">
                    <ul>
                        <!--stepbox1-->
                        <div class="stepbox1">
                            <li class="resume_item_title">システム管理ID（エージェント様自社管理用）</li>
                            <li class="resume_item_input">
                                <input type="text" class="input_text" placeholder="10文字以内ご記入ください。" maxlength="10" value="" name="agent_account_code" placeholder="" />
                            </li>
                            <li class="resume_item_title">名前<span>＊</span></li>
                            <li class="resume_item_input">
                                <input type="text" class="input_text" placeholder="100文字以内ご記入ください。"  maxlength="100" value="" name="name" placeholder="" required />
                            </li>
                            <li class="resume_item_title">性別<span>＊</span></li>
                            <li class="sex_item">
                                <span><input type="radio" id="radio1" value="1" name="sex" required /><label for="radio1" class="radio1-text">男</label></span>
                                <span><input type="radio" id="radio2" value="2" name="sex" required /><label for="radio2" class="radio2-text">女</label></span>
                            </li>
                            <li class="resume_item_title">生年月日<span>＊</span></li>
                            <li class="birthday_select">
                                <input type="text" autocomplete="off" data-format="yyyy-mm-dd" data-start-view="4" data-min-view="2" name="birthday" class="input_text birthday_date" required>
                            </li>
                            <li class="resume_item_title">国籍(地域)<span>＊</span></li>
                            <li class="stagnate_select">
                                <div class="select">
                                    <select id="select4" name="nationality_id" onchange="select4Change()" required>
                                        <option value="" selected>選択する</option>
                                        @foreach(config("code.resume.nationality") as $k => $v)
                                            @if($k > 0)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                            <li class="resume_item_input">
                                <input type="text" class="input_text" maxlength="50" name="nationality" placeholder="その他" />
                            </li>
                            <li class="resume_item_title">現住所<span>＊</span></li>
                            <li class="live_item">
                                <span><input type="radio" id="radio3" value="2" name="address" required><label for="radio3" class="radio3-text" required >海外在住</label></span>
                                <span><input type="radio" id="radio4" value="1" name="address" required><label for="radio4" class="radio4-text" required >日本在住</label></span>
                            </li>
                            <li class="residence_select">
                                <div class="select">
                                    <select id="select5" name="address_id" required>
                                        <option value="" selected>選択する</option>
                                        @foreach(config("code.resume.country_city") as $k => $v)
                                            @if($k > 0)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                            {{--<li class="resume_item_title visa_type">ビザ種類<span>＊</span></li>--}}
                            {{--<li class="experience_select visa_type">--}}
                            {{--<div class="select">--}}
                            {{--<select id="select8" onchange="select8Change()" name="visa_type">--}}
                            {{--<option disabled value="" selected>選択する</option>--}}
                            {{--@foreach(config("code.resume.visa_type") as $k => $v)--}}
                            {{--@if($k > 0)--}}
                            {{--<option value="{{$k}}">{{$v}}</option>--}}
                            {{--@endif--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</li>--}}
                            {{--<li class="resume_item_input visa_type">--}}
                            {{--<input type="text" class="input_text" name="visa_other" placeholder="その他" value="" />--}}
                            {{--</li>--}}
                            {{--<li class="resume_item_title visa_type">ビザ有効期限<span>＊</span></li>--}}
                            {{--<li class="birthday_select visa_type">--}}
                            {{--<input type="text" id="visa_term" data-start-view="3" data-min-view="2"  name="visa_term" class="input_text visa_date">--}}
                            {{--</li>--}}

                            <li class="resume_item_title stagnate_item_title">日本滞在年数<span>＊</span></li>
                            <li class="stagnate_select">
                                <div class="select">
                                    <select id="select6" name="address_extra_2" required>
                                        <option value="" selected>選択する</option>
                                        <option value="0">無</option>
                                        <option value="-1">1年未満</option>
                                        @for($i = 1; $i<10; $i++)
                                            <option value="{{$i}}">{{$i}}年以上</option>
                                        @endfor
                                        <option value="99">10年以上</option>
                                    </select>
                                </div>
                            </li>
                        </div>

                        <!--stepbox2-->
                        <div class="stepbox2">
                            <li class="resume_item_title">就業状況<span>＊</span></li>
                            <li class="employment_item">
                                <span><input type="radio" id="radio5" value="1" name="employment_status" required /><label for="radio5" class="radio5-text">在学中</label></span>
                                <span><input type="radio" id="radio6" value="2" name="employment_status" required /><label for="radio6" class="radio6-text">在職中</label></span>
                            </li>
                            <li class="resume_item_title select61">仕事経験年数<span>＊</span></li>
                            <li class="experience_select select61">
                                <div class="select">
                                    <select id="select61" name="employment_status_extra_2">
                                        <option value="" selected>選択する</option>
                                        <option value="-1">1年未満</option>
                                        @for($i = 1; $i<10; $i++)
                                            <option value="{{$i}}">{{$i}}年以上</option>
                                        @endfor
                                        <option value="99">10年以上</option>
                                    </select>
                                </div>
                            </li>

                            <li class="resume_item_title select62">卒業見込み<span>＊</span></li>
                            <li class="experience_select select62">
                                <div class="select">
                                    <select id="select62" name="employment_status_extra_1">
                                        <option value="" selected>選択する</option>
                                        @for($i = date('Y'); $i< date('Y') + 5; $i++)
                                            <option value="{{$i}}">{{$i}}年</option>
                                        @endfor
                                    </select>
                                </div>
                            </li>
                            <li class="resume_item_title">最終学歴<span>＊</span></li>
                            <li class="experience_select">
                                <div class="select">
                                    <select id="select7" name="final_education" required>
                                        <option value="" selected>選択する</option>
                                        @foreach(config("code.resume.final_education") as $k => $v)
                                            @if($k > 0)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                            <li class="resume_item_input">
                                <input type="text" class="input_text" maxlength="50" name="university" placeholder="学校名" required />
                            </li>
                            <li class="resume_item_title">学科専攻<span>＊</span></li>
                            <li class="education_select">
                                <input type="text" class="input_text" maxlength="50" name="major" required></input>
                            </li>
                            <li class="resume_item_title">文理区分<span>＊</span></li>
                            <li class="wenli_item">
                                @foreach(config("code.resume.science_arts") as $k => $v)
                                    @if($k > 0)
                                        <span><input type="radio" id="science_arts{{$k}}" value="{{$k}}" name="science_arts" required /><label for="science_arts{{$k}}">{{$v}}</label></span>
                                    @endif
                                @endforeach
                            </li>
                            {{--<li class="resume_item_title">面接対策指導を受けてみますか？（内容：履歴書・職務経歴書の書き方、自己PR、模擬面接など）<span>＊</span></li>--}}
                            {{--<li class="wenli_item">--}}
                            {{--@foreach(config("code.resume.interview") as $k => $v)--}}
                            {{--@if($k > 0)--}}
                            {{--<span><input type="radio" id="interview{{$k}}" value="{{$k}}" name="interview" required /><label for="interview{{$k}}">{{$v}}</label></span>--}}
                            {{--@endif--}}
                            {{--@endforeach--}}
                            {{--</li>--}}
                            <li class="resume_item_title">日本語レベル（JLPT）<span>＊</span></li>
                            <li class="japanese_item">
                                @foreach(config("code.resume.jp_level_2") as $k => $v)
                                    @if($k > 0)
                                        <span><input type="radio" id="jp_level{{$k}}" value="{{$k}}" name="jp_level" required /><label for="jp_level{{$k}}">{{$v}}</label></span>
                                    @endif
                                @endforeach
                            </li>
                            <li class="resume_item_title">英語レベル<span></span></li>
                            <li class="english_item">
                                @foreach(config("code.resume.en_level") as $k => $v)
                                    @if($k > 0 && $k < 4)
                                        <span><input type="radio" id="en_level{{$k}}" value="{{$k}}" name="en_level" /><label for="en_level{{$k}}" >{{$v}}</label></span>
                                    @endif
                                @endforeach
                            </li>
                            <li class="resume_item_title">TOEIC点数</li>
                            <li class="resume_item_input">
                                <input type="number" class="input_text" value="" name="toeic" />
                            </li>
                        </div>
                        <!--stepbox3-->
                        <div class="stepbox3">
                            <li class="resume_item_title">Eメールアドレス<span>＊</span></li>
                            <li class="resume_item_input">
                                <input type="email" class="input_text" maxlength="50" value="" name="email" required />
                            </li>
                            <li class="resume_item_title">携帯電話<span>＊</span></li>
                            <li class="resume_item_input">
                                <input type="tel" class="input_text" maxlength="50" value="" name="cell_phone" required />
                            </li>

                            <li class="resume_item_title">Line ID</li>
                            <li class="resume_item_input">
                                <input type="text" class="input_text" maxlength="50" value="" name="line_id" />
                            </li>

                            <li class="resume_item_title">WeChat ID</li>
                            <li class="resume_item_input">
                                <input type="text" class="input_text" maxlength="50" value="" name="wechat_id" />
                            </li>

                            <li class="resume_item_title">Skype ID</li>
                            <li class="resume_item_input">
                                <input type="text" class="input_text" maxlength="50" value="" name="skype_id" />
                            </li>
                        </div>

                        <!--stepbox4-->
                        <div class="stepbox4">
                            {{--<li class="resume_item_title">どのようにして「findjapanjob.com」を知りましたか？<span>＊</span></li>--}}
                            {{--<li class="channel_item">--}}
                            {{--@foreach(config("code.resume.know_way") as $k => $v)--}}
                            {{--@if($k > 0 and $k < 7)--}}
                            {{--<span><input type="radio" id="know_way{{$k}}" value="{{$k}}" name="know_way" required /><label for="know_way{{$k}}" class="radio7-text">{{$v}}</label></span>--}}
                            {{--@endif--}}
                            {{--@endforeach--}}
                            {{--<span><input type="radio" id="radio21" name="know_way" value="7"><label for="radio21" class="radio21-text">その他</label></span>--}}
                            {{--</li>--}}
                            {{--<input type="text" name="know_way_other" class="input_text other_text1" placeholder="その他" />--}}
                            <li class="resume_item_title">ITスキル（エンジニアの方のみ記入）<span></span></li>
                            <li class="skill_item">
                                @foreach(config("code.resume.it_skill") as $k => $v)
                                    @if($k > 0)
                                        <span><input type="checkbox" id="it_skill{{$k}}" class="it_skill" value="{{$k}}"><label for="it_skill{{$k}}">{{$v}}</label></span>
                                    @endif
                                @endforeach
                                <span><input type="checkbox" id="checkbox20" class="it_skill" value="99"><label for="checkbox20" class="checkbox20-text">その他</label></span>
                                <input type="text" class="input_text other_text2" name="it_skill_other" value="" placeholder="その他" />

                                <input type="hidden" name="it_skill">
                            </li>

                            <li class="resume_item_title">希望業種(複数回答可)<span></span></li>
                            <li class="skill_item">
                                @foreach(config("code.resume.desired_fileds") as $k => $v)
                                    @if($k > 0)
                                        <span><input type="checkbox" id="desired_fileds{{$k}}" class="desired_fileds" value="{{$k}}"><label for="desired_fileds{{$k}}">{{$v}}</label></span>
                                    @endif
                                @endforeach
                                <span><input type="checkbox" id="checkbox43" class="desired_fileds" value="99"><label for="checkbox43">その他</label></span>
                                <input type="text" class="input_text other_text3" name="desired_fileds_other" placeholder="その他" />
                                <input type="hidden" name="desired_fileds">
                            </li>

                            <li class="resume_item_title">希望職種(複数回答可)<span></span></li>
                            <li class="skill_item">
                                @foreach(config("code.resume.desired_job_type") as $k => $v)
                                    @if($k > 0)
                                        <span><input type="checkbox" id="desired_job_type{{$k}}" class="desired_job_type"  value="{{$k}}"><label for="desired_job_type{{$k}}">{{$v}}</label></span>
                                    @endif
                                @endforeach
                                <span><input type="checkbox" id="checkbox59" class="desired_job_type" value="99"><label for="checkbox99">その他</label></span>
                                <input type="text" name="desired_job_type_other" class="input_text other_text4" placeholder="その他" />
                                <input type="hidden" name="desired_job_type">
                            </li>
                            <li class="resume_item_title">希望勤務地(複数回答可)<span></span></li>
                            <li class="skill_item">
                                @foreach(config("code.resume.prefecture") as $k => $v)
                                    @if($k < 3)
                                        <span><input type="checkbox" id="prefecture{{$k}}" name="prefecture{{$k}}" class="prefecture" value="{{$k}}"><label for="prefecture{{$k}}">{{$v}}</label></span>
                                    @endif
                                @endforeach
                                <span><input type="checkbox" id="checkbox9999" name="checkbox9999" class="prefecture" value_str="日本全国" value="9999"><label for="checkbox9999" class="checkbox9999-text">日本全国</label></span>
                                <div class="select all_city">
                                    <select id="select11" name="country_city">
                                        <option value="" selected>選択する</option>
                                        @foreach(config("code.resume.country_city") as $k => $v)
                                            @if($k > 2)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="desired_place_ids">
                            </li>
                            <li class="resume_item_title">自己PRとその他希望条件等、自由にお書きください。<b>1000文字以内</b></li>
                            <li class="education_select">
                                <textarea maxlength="2000" class="text_area" id="text_area2" name="pr_other"></textarea>
                            </li>
                            <li class="resume_item_title">推薦文<b>500文字以内</b></li>
                            <li class="education_select">
                                <textarea maxlength="500" class="text_area" name="recommendation"></textarea>
                            </li>
                            <li class="privacy_item">
                                <p>情報の取扱いについては「<a href="#" id="popupTrigger3" data-popup-target="privacyPopup">ライバシーポリシー</a>」のページをご覧いただき、
                                    その内容に同意する方のみ「送信」を押してください</p>
                            </li>
                            <div class="step_pages_end"><input type="submit" class="resume_ok_btn" value="送信"></div>
                        </div>
                    </ul>
                </div>


            </div>
        </form>
    </div>
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

        // 表单验证
        $("#resume_add").html5Validate(function() {
            //it_skill
            var it_skill_arr =[];
            $('input[class="it_skill"]:checked').each(function(){
                it_skill_arr.push($(this).val());
            });

            if (it_skill_arr) {
                var it_skill_str = it_skill_arr.join(",");
                $("input[name='it_skill']").val(it_skill_str);
            }

            //desired_place_ids
            var working_place_arr =[];
            $('input[class="prefecture"]:checked').each(function(){
                working_place_arr.push($(this).val());
            });

            var select11_val = $('#select11 option:selected').val();
            if (select11_val) {
                working_place_arr.push(select11_val);
            }

            if (working_place_arr) {
                var working_place_str = working_place_arr.join(",");
                $("input[name='desired_place_ids']").val(working_place_str);
            }

            //desired_fileds
            var desired_fileds_arr =[];
            $('input[class="desired_fileds"]:checked').each(function(){
                desired_fileds_arr.push($(this).val());
            });

            if (it_skill_arr) {
                var desired_fileds_str = desired_fileds_arr.join(",");
                $("input[name='desired_fileds']").val(desired_fileds_str);
            }

            //desired_fileds
            var desired_job_type_arr =[];
            $('input[class="desired_job_type"]:checked').each(function(){
                desired_job_type_arr.push($(this).val());
            });

            if (desired_job_type_arr) {
                var desired_job_type_str = desired_job_type_arr.join(",");
                $("input[name='desired_job_type']").val(desired_job_type_str);
            }

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
            });
            var email = $("input[name='email']").val();
            var birthday = $("input[name='birthday']").val();
            var sex = $("input[name='sex']").val();
            var nationality_id = $("select[name='nationality_id']").val();
            var nationality = $("input[name='nationality']").val();
            var name = $("input[name='name']").val();
            $.ajax({
                type: "post",
                url: "{{ route('agent.resume.validatorItem') }}",
                dataType: "json",
                data: {"email":email, 'birthday':birthday, 'sex':sex, 'nationality_id':nationality_id, 'nationality':nationality, 'name':name},
                success: function(content){
                    if (content.status == 200) {
                        $("body").mLoading({
                            text:"送信中...",//加载文字
                            icon:"/images/loading.gif",//加载图标
                            mask:true//是否显示遮罩
                        });
                        document.getElementById("resume_add").submit();
                        return false;
                    } else {
                        layer.open({
                            content: content.msg,
                            btn: 'OK',
                            shadeClose: false,
                            yes: function(index) {
                                layer.close(index);
                                return false;
                            }
                        });
                    }
                },
                error: function (err){
                    console.log(err);
                }
            });
        }, {

            validate: function() {
                var toeic = $("input[name='toeic']").val();
                if (toeic && (!/^[\d]{1,4}$/.test(toeic) || toeic > 999)) {
                    $("input[name='toeic']").testRemind("正しい点数をご入力ください。");
                    $("input[name='toeic']").focus();
                    return false;
                }

                return true;
            }

        });
        // 表单本地保存
        $(function() {
            $(".visa_type").hide();
            $(".select61").hide();
            $(".select62").hide();
            $(".stagnate_item_title").show();
            $(".stagnate_select").show();
            var id = "{{$id}}";
            if (id > 0) {
                layer.open({
                    content: "どうもありがとうございました。ご登録されたメールアドレスにログインIDとパスワードをご送付致しました。",
                    btn: 'OK',
                    shadeClose: false,
                    yes: function(index){
                        layer.close(index);
                        window.location.href = "{{route('agent.index.index')}}";
                    }
                });
            }
            //$('#resume_add').formKeeper();

            $('#checkbox9999').on('ifChecked', function(event) {
                $('#prefecture1, #prefecture2').iCheck('disable');
                $("#select11").attr("disabled","disabled");
            });
            $('#checkbox9999').on('ifUnchecked', function(event) {
                $('#prefecture1, #prefecture2').iCheck('enable');
                $("#select11").removeAttr("disabled");
            });

            $('.birthday_date').fdatepicker({initialDate:'1990-01-01', endDate: new Date()});
            $('.visa_date').fdatepicker({startDate: new Date()});

            select4Change();
            select8Change();

        });

        //弹框
        $('#popupTrigger2').popup();
        $('#popupTrigger3').popup();

        $(document).ready(function() {
            //复选框,单选框
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
            });

            $(":radio,:checkbox").css("visibility","visible");
            $(":radio,:checkbox").css("opacity","0");


            //現住所:日本
            $('#radio3').on('ifChecked', function(event) {
                $('.residence_select,.visa_type').hide();
                $("#select5").removeAttr("required");
                //$("#select6").removeAttr("required");

                $("#select8").removeAttr("required");
                $("#visa_term").removeAttr("required");
            });

            $('#radio4').on('ifChecked', function(event) {
                $('.residence_select,.visa_type').show();
                $("#select5").attr("required", "true");
                //$("#select6").attr("required", "true");

                $("#select8").attr("required", "true");
                $("#visa_term").attr("required", "true");
            });
            $('#radio4').on('ifUnchecked', function(event) {
                $('.residence_select,.visa_type').hide();
                $("#select5").attr("required", "false");
                //$("#select6").attr("required", "false");

                $("#select8").attr("required", "false");
                $("#visa_term").attr("required", "false");
            });

            //就業状況
            $('#radio5').on('ifChecked', function(event) {
                $('.select62').show();
                $("#select62").attr("required", "true");

                $('.select61').hide();
                $("#select61").removeAttr("required");
            });

            $('#radio6').on('ifChecked', function(event) {
                $('.select61').show();
                $("#select61").attr("required", "true");

                $('.select62').hide();
                $("#select62").removeAttr("required");
            });

            //其他

            $('#radio21').on('ifChecked', function(event) {
                $('.other_text1').show();

            });
            $('#radio21').on('ifUnchecked', function(event) {
                $('.other_text1').hide();
            });

            $('#checkbox20').on('ifChecked', function(event) {
                $('.other_text2').show();
                //$('.other_text2').val()='0000';
                console.log($('.other_text2').val());
            });
            $('#checkbox20').on('ifUnchecked', function(event) {
                $('.other_text2').hide();
                $('.other_text2').attr('disabled', 'disabled')

            });
            $('#checkbox43').on('ifChecked', function(event) {
                $('.other_text3').show();
            });
            $('#checkbox43').on('ifUnchecked', function(event) {
                $('.other_text3').hide();
            });

            $('#checkbox59').on('ifChecked', function(event) {
                $('.other_text4').show();
            });
            $('#checkbox59').on('ifUnchecked', function(event) {
                $('.other_text4').hide();
            });

            //日本全国
            $('#checkbox62').on('ifChecked', function(event) {
                $('.all_city').show();
            });
            $('#checkbox62').on('ifUnchecked', function(event) {
                $('.all_city').hide();
            });

            $(".stepbtn_1").click(function() {
                $(".stepbox1").hide();
                $(".stepbox2").show();
                window.scroll(0, 0);
            });

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

        function select8Change() {
            var text = $("#select8 option:selected").text();
            if (text == "その他") {
                $("input[name='visa_other']").show();
                $("input[name='visa_other']").attr("required", "true");
            } else {
                $("input[name='visa_other']").removeAttr("required");
                $("input[name='visa_other']").hide();
            }
        }

    </script>

    <script type="text/javascript" src="{{asset('/js/jquery.autoTextarea.js')}}"></script>

@endsection