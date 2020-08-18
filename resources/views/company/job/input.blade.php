@extends('layouts.company')
@section('content')

    <link type="text/css" href="{{asset('/css/formSelects-v4.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('/js/formSelects-v4.min.js')}}"></script>

    <div class="main">

        <form id="testForm" action="" method="post" accept-charset="UTF-8" name="testForm">
            {{csrf_field()}}
            <input type="hidden" name="data[job_id]" value="{{ $data->job_id }}">
            <div class="post_info_body">

                <div class="post_btn_item">
                    <h3><a onclick="level('{{ route("company.job.index") }}')" href="javascript:void(0);" class="back_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> 戻る</a>@if($data->job_id)求人管理ID：{{$data->account_code}}@else求人票新規作成@endif</h3>
                    <ul>
                        <li><a href="javascript:void(0)" onclick="save()" class="cr_save_btn"><i class="fa fa-floppy-o" aria-hidden="true"></i> 保存/更新</a></li>
                        <li><a href="javascript:void(0)" onclick="preview()" class="cr_view_btn"><i class="fa fa-eye" aria-hidden="true"></i>プレビュー</a></li>
                        @if($data->job_id)
                            <li><a href="javascript:void(0)" onclick="copy()" class="cr_copy_btn"><i class="fa fa-files-o" aria-hidden="true"></i> コピー</a></li>
                            <li><a href="javascript:void(0)" onclick="delete_obj()" class="cr_del_btn"><i class="fa fa-trash" aria-hidden="true"></i> 削除</a></li>
                        @endif
                    </ul>
                </div>
                <div class="post_info_content">
                    <ul>
                        <li class="item_title">システム管理ID</li>
                        <li class="item_input">
                            <input type="text" class="input_text" value="{{$data->job_code}}" name="data[job_code]" data-max="100" />
                        </li>
                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">掲載期間<span>＊</span></li>
                                <li class="item_input">
                                    <input type="text" autocomplete="off" readonly class="input_text c_m_date_start" name="data[job_period_start]" placeholder="開始日" value="{{$data->job_period_start}}" data-start-view="4" data-min-view="2" />
                                    <span class="date_line">-</span>
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title"></li>
                                <li class="item_input">

                                    <input type="text" autocomplete="off" readonly class="input_text c_m_date_end" name="data[job_period_end]" placeholder="終了日" value="{{$data->job_period_end}}" data-start-view="4" data-min-view="2" />


                                    <input type="hidden" name="data[job_period_start_hide]">
                                    <input type="hidden" name="data[job_period_end_hide]">
                                </li>
                            </ul>
                        </li>

                        <li class="item_input">
                            @foreach($jobInfo['job_period_type'] as $k => $v)
                                <span><input type="radio" id="c_m_date_type{{$k}}" name="data[job_period_type]" @if($data->job_period_type == $k || (!$data->job_period_type && $k == 1)) checked @endif value="{{$k}}" onchange="job_period_typeSelect()"><label for="c_m_date_type{{$k}}">{{$v}}</label></span>
                            @endforeach
                        </li>
                        <li class="item_title">募集職種名<span>＊</span></li>
                        <li class="item_input">
                            <input type="text" class="input_text" value="{{$data->job_name}}" name="data[job_name]" data-max="100" required />
                        </li>

                        
                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">職種カテゴリ</li>
                                <li class="item_input">
                                    <div class="select">
                                        <select onchange="jobCategorySelect()" id="position_class" name="data[job_category]">
                                            <option value="">選択する</option>
                                            @foreach($resumeInfo['desired_job_type'] as $k => $v)
                                                @if($k > 0)
                                                    <option value="{{$k}}" @if($data->job_category == $k) selected @endif>{{$v}}</option>
                                                @endif
                                            @endforeach
                                            <option value="99" @if($data->job_category == 99) selected @endif>その他</option>
                                        </select>
                                    </div>
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title"></li>
                                <li class="item_input"><input type="text" class="input_text" value="{{$data->job_other}}" name="data[job_other]" placeholder="その他" data-max="100" />
                                </li>
                            </ul>
                        </li>
                        <li class="item_title">仕事内容<span>＊</span></li>
                        <li class="item_input">
                            <textarea class="text_area" maxlength="2000" id="job_content" name="data[jp_detail]" required>{{$data->jp_detail}}</textarea>
                        </li>

                        <fieldset>
                            <legend>応募資格</legend>
                            <li class="cols2">
                                <ul class="cols_item">
                                    <li class="item_title">日本語レベル<span>＊</span></li>
                                    <li class="item_input">
                                        <div class="select">
                                            <select name="data[jp_level_2]" required>
                                                <option value="" selected>選択する</option>
                                                @foreach($resumeInfo['jp_level_2'] as $k => $v)
                                                    @if($k > 0)
                                                        <option value="{{$k}}" @if($data->jp_level_2 == $k) selected @endif>{{$v}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                                <ul class="cols_item">
                                    <li class="item_title"></li>
                                    <li class="item_input">
                                        <div class="select">
                                            <select name="data[jp_level]" required>
                                                <option value="" selected>選択する</option>
                                                @foreach($resumeInfo['jp_level'] as $k => $v)
                                                    @if($k > 0)
                                                        <option value="{{$k}}" @if($data->jp_level == $k) selected @endif>{{$v}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="item_title">英語レベル</li>
                            <li class="item_input">
                                <div class="select">
                                    <select id="eng_lang" name="data[en_level]">
                                        <option value="" selected>選択する</option>
                                        @foreach($resumeInfo['en_level'] as $k => $v)
                                            @if($k > 0)
                                                <option value="{{$k}}" @if($data->en_level == $k) selected @endif>{{$v}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                            <li class="item_title">その他</li>
                            <li><textarea class="text_area" maxlength="1000" id="other_lang" name="data[en_level_other]">{{$data->en_level_other}}</textarea></li>
                        </fieldset>


                        <li class="item_title">募集対象</li>
                        <li class="item_input">
                            @php
                                $target = explode(",", $data->target);
                            @endphp
                            @foreach($resumeInfo['target'] as $k => $v)
                                @if ($k > 0)
                                    <span><input type="checkbox" class="checkbox_target" onclick="targetChange()" id="checkbox_target{{$k}}" name="target" value="{{$k}}" @if(in_array($k, $target)) checked @endif><label for="checkbox_target{{$k}}" class="checkbox1-text">{{$v}}</label></span>
                                @endif
                            @endforeach
                            <input type="hidden" name="data[target]">
                        </li>
                        <li class="cols2">
                            {{--<ul class="cols_item">--}}
                                {{--<li class="item_input">--}}
                                    {{--<div class="select">--}}
                                        {{--<select id="target_select" name="data[nationality]" required onchange="nationalitySelect()">--}}
                                            {{--<option disabled value="" selected>「国・地域」名をご記入 ください</option>--}}
                                            {{--@foreach($resumeInfo['nationality'] as $k => $v)--}}
                                                {{--@if($k > 0)--}}
                                                    {{--<option value="{{$k}}" @if($data->nationality == $k) selected @endif>{{$v}}</option>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                            <ul class="cols_item cols_item2">
                                <li class="item_input nationality_other"><input type="text" class="input_text" value="{{$data->nationality_other}}" name="data[nationality_other]" placeholder="「国・地域」名をご記入 ください" data-max="100" />
                                </li>
                                <li class="item_text">籍大歓迎</li>
                            </ul>
                        </li>

                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">対象年齢</li>
                                <li class="item_input">
                                    <div class="select">
                                        <select id="target_ages_start" name="data[age_start]">
                                            <option value="" selected>選択する</option>
                                            @for($i = 18; $i <= 50; $i++)
                                                @if($k > 0)
                                                    <option value="{{$i}}" @if($data->age_start == $i) selected @endif>{{$i}}歳</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                    <span class="date_line">-</span>
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title"></li>
                                <li class="item_input">
                                    <div class="select">
                                        <select id="target_ages_end" name="data[age_end]">
                                            <option value="" selected>選択する</option>
                                            @for($i = 18; $i <= 50; $i++)
                                                @if($k > 0)
                                                    <option value="{{$i}}" @if($data->age_end == $i) selected @endif>{{$i}}歳</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="item_title">年齢制限理由<a  class="zebra_tips1" title="1、長期勤続によるキャリアの形成。</br> 2、技能・ノウ ハウの継承。"><i class="fa fa-pencil" aria-hidden="true"></i> 入力例</a></li>
                        <li><textarea class="text_area" maxlength="100" id="target_ages_other" name="data[age_other]">{{$data->age_other}}</textarea></li>
                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">海外在住者の採用</li>
                                <li class="item_input">
                                    <span><input type="checkbox" id="overseas_residents" name="data[employment_overseas]" value="1" @if($data->employment_overseas == 1) checked @endif><label for="overseas_residents" class="overseas_residents">有り</label></span>
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title">就労ビザのサポート</li>
                                <li class="item_input">
                                    <span><input type="checkbox" id="employment_visa_support" name="data[working_visa]" value="1" @if($data->working_visa == 1) checked @endif><label for="employment_visa_support" class="employment_visa_support">有り</label></span>
                                </li>
                            </ul>
                        </li>
                        <li class="item_title">雇用形態<span>＊</span></li>
                        <li class="item_input">
                            @php
                                $working_form = explode(",", $data->working_form);
                            @endphp
                            @foreach($resumeInfo['working_form'] as $k => $v)
                                @if ($k > 0)
                                    <span><input type="checkbox" id="employment{{$k}}" name="working_form" value="{{$k}}"  @if(in_array($k, $working_form)) checked @endif><label for="employment{{$k}}">{{$v}}</label></span>
                                @endif
                            @endforeach
                            <input type="hidden" name="data[working_form]">
                        </li>
                        <fieldset>
                            <legend>給与</legend>
                            <li class="cols2">
                                <ul class="cols_item">
                                    <li class="item_title">年俸</li>
                                    <li class="item_input">
                                        <div class="select">
                                            <select id="salary_select_start" name="data[yearly_income_low]">
                                                <option value="" selected>選択する</option>
                                                @foreach($resumeInfo['wage_arr_1'] as $k => $v)
                                                    @if($k > 0 && $v != "上限なし")
                                                        <option value="{{$k}}" @if($data->yearly_income_low == $k) selected @endif>{{$v}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="date_line">-</span>
                                    </li>
                                </ul>
                                <ul class="cols_item">
                                    <li class="item_title"></li>
                                    <li class="item_input">
                                        <div class="select">
                                            <select id="salary_select_end" name="data[yearly_income_up]">
                                                <option value="" selected>選択する</option>
                                                @foreach($resumeInfo['wage_arr_1'] as $k => $v)
                                                    @if($k > 0)
                                                        <option value="{{$k}}" @if($data->yearly_income_up == $k) selected @endif>{{$v}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="cols2">
                                <ul class="cols_item">
                                    <li class="item_title">月給</li>
                                    <li class="item_input">
                                        <div class="select">
                                            <select id="monthly_select_start" name="data[monthly_income_low]">
                                                <option value="" selected>選択する</option>
                                                @foreach($resumeInfo['wage_arr_2'] as $k => $v)
                                                    @if($k > 0 && $v != "上限なし")
                                                        <option value="{{$k}}" @if($data->monthly_income_low == $k) selected @endif>{{$v}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="date_line">-</span>
                                    </li>
                                </ul>
                                <ul class="cols_item">
                                    <li class="item_title"></li>
                                    <li class="item_input">
                                        <div class="select">
                                            <select id="monthly_select_end" name="data[monthly_income_up]">
                                                <option value="" selected>選択する</option>
                                                @foreach($resumeInfo['wage_arr_2'] as $k => $v)
                                                    @if($k > 0)
                                                        <option value="{{$k}}" @if($data->monthly_income_up == $k) selected @endif>{{$v}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="cols2">
                                <ul class="cols_item">
                                    <li class="item_title">時給</li>
                                    <li class="item_input">
                                        <div class="select">
                                            <select id="hourly_select_start" name="data[hourly_from]">
                                                <option value="" selected>選択する</option>
                                                @foreach($resumeInfo['wage_arr_3'] as $k => $v)
                                                    @if($k > 0 && $v != "上限なし")
                                                        <option value="{{$k}}" @if($data->hourly_from == $k) selected @endif>{{$v}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="date_line">-</span>
                                    </li>
                                </ul>
                                <ul class="cols_item">
                                    <li class="item_title"></li>
                                    <li class="item_input">
                                        <div class="select">
                                            <select id="hourly_select_end" name="data[hourly_to]">
                                                <option value="" selected>選択する</option>
                                                @foreach($resumeInfo['wage_arr_3'] as $k => $v)
                                                    @if($k > 0)
                                                        <option value="{{$k}}" @if($data->hourly_to == $k) selected @endif>{{$v}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="item_title">その他</li>
                            <li><textarea class="text_area" maxlength="1000" id="wages_other" name="data[yearly_income_memo]">{{$data->yearly_income_memo}}</textarea></li>
                        </fieldset>

                        <fieldset>
                            <legend>勤務地</legend>
                            <li class="item_title">都道府県<span>＊</span></li>
                            <li class="item_input">
                                    <select xm-select="workplace_select" xm-select-search="" id="workplace_select" name="data[prefecture]">
                                        <option value="">選択する</option>
                                        @php
                                            $prefecture = explode(",", $data->prefecture );
                                        @endphp
                                        @foreach($resumeInfo['country_city'] as $k => $v)
                                            @if($k > 0)
                                                <option value="{{$k}}" @if(in_array($k, $prefecture)) selected @endif>{{$v}}</option>
                                            @endif
                                        @endforeach
                                        <option value="99" @if(in_array(99, $prefecture)) selected @endif>その他</option>
                                    </select>
                            </li>
                            <li class="item_input prefecture_other"><input type="text" maxlength="20" class="input_text" name="data[prefecture_other]" value="{{$data->prefecture_other}}" placeholder="その他">
                            </li>

                            <li class="item_title">住所</li>
                            <li class="item_input"><input type="text" class="input_text" value="{{$data->working_place}}" name="data[working_place]" data-max="200" />
                            </li>

                            <li class="item_title">その他</li>
                            <li><textarea class="text_area" id="workplace_other" name="data[working_place_other]" placeholder="その他" maxlength="500" data-max="500">{{$data->working_place_other}}</textarea></li>
                        </fieldset>

                        <li class="item_title">勤務時間</li>
                        <li><textarea class="text_area" id="work_times" name="data[working_time_all]" maxlength="500">{{$data->working_time_all}}</textarea></li>

                        <li class="item_title">休日・休暇</li>
                        <li><textarea class="text_area" id="rest_times" name="data[working_time_holiday]"  maxlength="1000">{{$data->working_time_holiday}}</textarea></li>

                        <li class="item_title">福利厚生</li>
                        <li><textarea class="text_area" id="welfare" name="data[welfare]" maxlength="1000">{{$data->welfare}}</textarea></li>

                        <li class="item_title">選考プロセス</li>
                        <li><textarea class="text_area" id="selection_procedure" name="data[interview_process]" maxlength="500">{{$data->interview_process}}</textarea></li>
                        <li class="item_title">その他</li>
                        <li><textarea class="text_area" id="other_content" name="data[others]" maxlength="2000">{{$data->others}}</textarea></li>

                        <li>求人情報については「<a href="#" id="popupTrigger3" data-popup-target="privacyPopup">掲載ガイドライン</a>」のペー ジをご覧いただき、その内容に同意する方のみ「保存/更新」を 押してください。</a>
                        </li>

                        <div class="step_pages_end"><input type="submit" class="resume_ok_btn" value="保存/更新"></div>

                    </ul>
                </div>
            </div>
        </form>
    </div>

    <div class="popup" tabindex="-3" role="dialog" data-popup-id="privacyPopup">
        <div class="popup__container">
            <div class="popup__content">
                <div class="privacy_content" role="dialog" data-popup-id="demoPopup">
                    <h3>掲載ガイドライン</h3>
                    <div class="privacy_text">
                        <p>１．ハイナビズ株式会社（以下「当社」といいます）が運営する就職・転職サイト「www.findjapanjob.com」（以下「findjapanjob」といいます）を利用する方（以下「利用者」といいます）は、利用者及び求人の情報をfindjapanjobの管理画面より入力することで求人情報を掲載することができます。</p>
                        <p>２．当社はfindjapanjobを利用して行った求人活動の結果について、一切の責任を負いません。</p>
                        <p>３．入力された情報が、当社の定める基準に反すると当社が判断した場合には、予告なく掲載を停止する場合があります。</p>
                        <p>４．利用者は、findjapanjobを求人活動においてのみ利用し、またその利用に際し職業安定法、労働基準法、その他労働法規等を遵守するものとします。</p>
                        <p>５．利用者は、findjapanjobによる応募者の中、選考状況は「書類選考中」以降である方からの質問・応募・その他の連絡については、原則１週間以内に返信を行うものとします。</p>
                        <p>６．利用者は、findjapanjobを通じて応募があった場合、選考状況は「書類選考中」以降である応募者に選考結果または採否を明示的に連絡するものとします。</p>
                        <p>７．利用者は、findjapanjobを通じて受領した個人情報について、個人情報の保護に関する法律等の法令の定めに従い、十分に注意して取り扱うものとし、採用目的以外の利用はしないものとします。</p>
                        <p>８．以下の様な求人情報の掲載は、当社の判断によりお断りする場合があります。<br/>
                            　（1）出会い系サイト（インターネットや携帯電話向けのアダルト系コンテンツを含むもの）<br/>
                            　（2）消費者金融<br/>
                            　（3）ギャンブルならびにその類似産業（パチンコ、競馬、競艇、オートレースは除く）<br/>
                            　（4）ネットワークビジネスなどの利殖を目的とした投資・代理店等に関わる斡旋・勧誘・募集等<br/>
                            　（5）政党及び政治団体の運動に関連するもの<br/>
                            　（6）宗教団体の勧誘または、布教活動に関連するもの<br/>
                            　（7）法令に違反するもの<br/>
                            　（8）公序良俗に反するもの<br/>
                            　（9）セミナーやイベントの告知など求人以外の告知<br/>
                            　（10）その他、当社が不適当と判断したもの<br/>
                        </p>
                        <p>９．一度掲載をした情報についても、その後当社が不適当と判断した場合は予告なく掲載を停止する場合があります。なお、当社は、上記判断に関する理由を開示する義務は負いません。</p>
                        <div class="pop_btn">
                            <input type="button" value="確定" class="pop_ok_btn" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $('#popupTrigger3').popup();

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });

        $(document).ready(function() {
            $('.c_m_date_start').fdatepicker();
            $('.c_m_date_end').fdatepicker();
            //复选框,单选框
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
            });

            $("input[name='data[job_period_type]']").on('ifChecked',function(event) {
                job_period_typeSelect();
            });

            formSelects.btns('workplace_select', []);

            formSelects.on('workplace_select', function(id, vals, val, isAdd, isDisabled){
                if (val.value == 99) {
                    if (isAdd === true) {
                        prefectureSelect(99);
                    } else {
                        prefectureSelect(0);
                    }
                }
            });

            @if(in_array(99, $prefecture))
            prefectureSelect(99);
            @else
            prefectureSelect(0);
            @endif

            job_period_typeSelect();
            jobCategorySelect();
            //nationalitySelect();
        });

        //提示信息框
        var zt = new $.Zebra_Tooltips($('.zebra_tips1'));
        $(".zebra_tips1").click(function() {
            zt.show($('.zebra_tips1'), true);
        });

        function prefectureSelect(val) {
            if (val == 99) {
                $(".prefecture_other").show();
                $("input[name='data[prefecture_other]']").attr("required", "true");
            } else {
                $("input[name='data[prefecture_other]']").removeAttr("required");
                $(".prefecture_other").hide();
            }
        }

        function job_period_typeSelect() {
            var val = $("input[name='data[job_period_type]']:checked").val();
            if (val != 1) {
                $("input[name='data[job_period_start]']").removeAttr("required");
                $("input[name='data[job_period_end]']").removeAttr("required");

                $("input[name='data[job_period_start]']").attr("disabled", "disabled");
                $("input[name='data[job_period_end]']").attr("disabled", "disabled");

                $("input[name='data[job_period_start]']").addClass("c_m_date_disabled");
                $("input[name='data[job_period_end]']").addClass("c_m_date_disabled");
            } else {
                $("input[name='data[job_period_start]']").attr("required", "true");
                $("input[name='data[job_period_end]']").attr("required", "true");

                $("input[name='data[job_period_start]']").removeAttr("disabled");
                $("input[name='data[job_period_end]']").removeAttr("disabled");

                $("input[name='data[job_period_start]']").removeClass("c_m_date_disabled");
                $("input[name='data[job_period_end]']").removeClass("c_m_date_disabled");
            }
        }

        function jobCategorySelect() {
            var val = $("select[name='data[job_category]']").val();
            if (val == 99) {
                $("input[name='data[job_other]']").show();
                $("input[name='data[job_other]']").attr("required", "true");
            } else {
                $("input[name='data[job_other]']").removeAttr("required");
                $("input[name='data[job_other]']").hide();
            }
        }

        function nationalitySelect() {
            var val = $("select[name='data[nationality]'] option:selected").text();
            if (val == "その他") {
                $(".nationality_other").show();
                $("input[name='data[nationality_other]']").attr("required", "true");
            } else {
                $("input[name='data[nationality_other]']").removeAttr("required");
                $(".nationality_other").hide();
            }
        }

        function save() {
            $("#testForm").submit();
        }

        function preview() {
            var job_id = "{{ $data->job_id }}";
            if (job_id) {
                layer.open({
                    content: "{{ config('code.alert_msg.web.job_preview') }}"
                    ,btn: ['はい', 'いいえ']
                    ,yes: function(index){
                        var url = "{{ route('job.detail', [$data->job_id]) . '?preview=1' }}";
                        window.open(url);
                        layer.close(index);
                    }
                    ,no: function(index){
                        layer.close(index);
                    }
                });
            } else {
                layer.open({
                    content: "{{ config('code.alert_msg.web.job_preview_input') }}",
                    btn: 'OK',
                    shadeClose: false,
                    yes: function(index) {
                        layer.close(index);
                    }
                });
            }
        }

        function copy() {
            $.ajax({
                type: "post",
                url: "{{ route('company.job.copy') }}",
                dataType: "json",
                data: {'job_id': "{{ $data->job_id }}"},
                success: function(content){
                    if (content.status == 200) {
                        layer.open({
                            content: content.msg
                            ,btn: ['はい', 'いいえ']
                            ,yes: function(index){
                                layer.close(index);

                                var url = content.url;
                                layer.open({
                                    content: "{{ config('code.alert_msg.web.level_page') }}"
                                    ,btn: ['はい', 'いいえ']
                                    ,yes: function(index1){
                                        layer.close(index1);
                                        window.location.href = url;
                                    }
                                    ,no: function(index1){
                                        layer.close(index1);
                                    }
                                });
                                return false;
                            }
                            ,no: function(index){
                                layer.close(index);
                                return false;
                            }
                        });
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
        }

        function delete_obj() {
            layer.open({
                content: "削除しますか？"
                ,btn: ['はい', 'いいえ']
                ,yes: function(index){
                    layer.close(index);
                    $.ajax({
                        type: "post",
                        url: "{{ route('company.job.delete') }}",
                        dataType: "json",
                        data: {'job_id': "{{ $data->job_id }}"},
                        success: function(content){
                            layer.open({
                                content: content.msg,
                                btn: 'OK',
                                shadeClose: false,
                                yes: function(index) {
                                    layer.close(index);
                                    if (content.status == 200) {
                                        window.location.href = "{{ route("company.job.index") }}";
                                    }
                                }
                            });
                        },
                        error: function (err){
                            console.log(err);
                        }
                    });
                }
                ,no: function(index){
                    layer.close(index);
                    return false;
                }
            });

        }

        $("#testForm").html5Validate(function() {
            $.ajax({
                type: "post",
                url: $("#testForm").attr("action"),
                dataType: "json",
                data: $("#testForm").serialize(),
                success: function(content){
                    layer.open({
                        content: content.msg,
                        btn: 'OK',
                        shadeClose: false,
                        yes: function(index) {
                            layer.close(index);
                            {{--if (content.status == 200) {--}}
                                {{--window.location.href = "{{ route("company.job.index") }}";--}}
                            {{--}--}}
                        }
                    });
                },
                error: function (err){
                    console.log(err);
                }
            });

        }, {
            validate: function() {
                var job_period_type = $("input[name='data[job_period_type]']:checked").val();
                var job_period_start = $("input[name='data[job_period_start]']").val();
                var job_period_end = $("input[name='data[job_period_end]']").val();

                $("input[name='data[job_period_start_hide]']").val(job_period_start);
                $("input[name='data[job_period_end_hide]']").val(job_period_end);

                if (job_period_type == 1 && Date.parse(job_period_start) > Date.parse(job_period_end)) {
                    $("input[name='data[job_period_start]']").testRemind("範囲選択が不正です");
                    $("input[name='data[job_period_start]']").focus();
                    return false;
                }

                var working_place_arr = [];
                $("input[name='target']:checked").each(function () {
                    working_place_arr.push($(this).val());
                });

                if (working_place_arr.length > 0) {
                    $("input[name='data[target]']").val(working_place_arr.join(","));
                }
                // else {
                //     $("input[name='target']").testRemind("ご選択ください");
                //     $("input[name='target']").focus();
                //     return false;
                // }

                var working_form_arr = [];
                $("input[name='working_form']:checked").each(function () {
                    working_form_arr.push($(this).val());
                });

                if (working_form_arr.length > 0) {
                    $("input[name='data[working_form]']").val(working_form_arr.join(","));
                } else {
                    $("input[name='working_form']").testRemind("ご選択ください");
                    $("input[name='working_form']").focus();
                    return false;
                }

                var age_start = $("select[name='data[age_start]']").val();
                var age_end = $("select[name='data[age_end]']").val();
                if ((age_start || age_end) && parseInt(age_start) > parseInt(age_end)) {
                    $("select[name='data[age_start]']").testRemind("正しい内容をご記入ください。");
                    $("select[name='data[age_start]']").focus();
                    return false;
                }

                if (age_start || age_end) {
                    var age_other = $("textarea[name='data[age_other]']").val();
                    if (!age_other) {
                        $("textarea[name='data[age_other]']").testRemind("ご記入ください");
                        $("textarea[name='data[age_other]']").focus();
                        return false;
                    }
                }

                var age_start = $("select[name='data[yearly_income_low]']").val();
                var age_end = $("select[name='data[yearly_income_up]']").val();
                if ((age_start || age_end) && parseInt(age_start) > parseInt(age_end)) {
                    $("select[name='data[yearly_income_low]']").testRemind("正しい内容をご記入ください。");
                    $("select[name='data[yearly_income_low]']").focus();
                    return false;
                }

                var age_start = $("select[name='data[monthly_income_low]']").val();
                var age_end = $("select[name='data[monthly_income_up]']").val();
                if ((age_start || age_end) && parseInt(age_start) > parseInt(age_end)) {
                    $("select[name='data[monthly_income_low]']").testRemind("正しい内容をご記入ください。");
                    $("select[name='data[monthly_income_low]']").focus();
                    return false;
                }

                var age_start = $("select[name='data[hourly_from]']").val();
                var age_end = $("select[name='data[hourly_to]']").val();
                if ((age_start || age_end) && parseInt(age_start) > parseInt(age_end)) {
                    $("select[name='data[hourly_from]']").testRemind("正しい内容をご記入ください。");
                    $("select[name='data[hourly_from]']").focus();
                    return false;
                }

                var prefecture = $("input[name='data[prefecture]']").val();
                if (!prefecture) {
                    $("input[name='data[prefecture]']").testRemind("ご選択ください");
                    $("input[name='data[prefecture]']").focus();
                    return false;
                }

                return true;
            }
        });

        function level(url)
        {
            layer.open({
                content: "{{ config('code.alert_msg.web.level_page') }}"
                ,btn: ['はい', 'いいえ']
                ,yes: function(index){
                    layer.close(index);
                    window.location.href = url;
                }
                ,no: function(index){
                    layer.close(index);
                }
            });
            return false;
        }

    </script>

    <script type="text/javascript" src="{{asset('/js/jquery.autoTextarea.js')}}"></script>

@endsection