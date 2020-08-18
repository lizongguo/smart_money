@extends('layouts.web')
@section('content')
    <link rel="stylesheet" href="{{asset('/css/theme/index.css')}}">
    <script src="{{asset('/js/new/element-ui.min.js')}}"></script>
    <script src="{{asset('/js/new/ja.js')}}"></script>
    <script src="{{asset('/js/ajaxfileupload.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/new/common.js')}}?v={{date('Y-m-d')}}"></script>
    <link rel="stylesheet" href="{{asset('/css/zebra_tooltips.css')}}" type="text/css">
    <script type="text/javascript" src="{{asset('/js/zebra_tooltips.js')}}"></script>
    <style>
        .el-date-editor.el-input, .el-date-editor.el-input__inner{
            width: 100%;
        }
        .el-input__inner{
            border: 0px;
        }
        .el-checkbox__label {
            line-height: 40px;
            font-size: 16px;
        }
        .el-checkbox__inner,.el-radio__inner{
            width: 18px;
            height: 18px;
        }
        .el-checkbox__inner::after {
            height: 9px;
            left: 6px;
            top: 2px;
        }
        .el-radio__label {
            line-height: 1.2;
            font-size: 16px;
        }
        .el-radio__inner::after {
            width: 4px;
            height: 4px;
        }
        .el-radio__input.is-checked+.el-radio__label,.el-checkbox__input.is-checked+.el-checkbox__label{
            color: #1d398b;
        }
        .el-radio__input.is-checked .el-radio__inner,.el-checkbox__input.is-checked .el-checkbox__inner {
            border-color: #1d398b;
            background: #1d398b;
        }
        
    </style>
    <div style="display: none">
        <iframe id="downloadPdf" NAME="downloadPdf"></iframe>
    </div>
    <div id="app">
        <div class="main">
            <div class="my_main">
                <div class="my_body">
                    <ul class="side_menu">
                        <li><a href="javascript:void(0);" onclick="$('#saveData').trigger('click');" class="cr_save_btn"><i class="fa fa-floppy-o"  aria-hidden="true"></i> 保存/更新</a></li>
                        <li><a @click="levelPage('{{route('web.experiences.uploadVideo', ['upload' => 1])}}')" class="cr_view_btn"><i class="fa fa-id-card-o" aria-hidden="true"></i>自己紹介映像</a></li>
                        <!-- <li><a target="downloadPdf" href="{{ URL::route('web.experiences.download',['t' => time()]) }}" class="cr_copy_btn"><i class="fa fa-file-text-o" aria-hidden="true"></i> PDF</a></li> -->
                    </ul>
                    @include('web.common.user_menu')

                    <p class="final_update" v-if="edit.id > 0">
                        最终更新日:
                        <template><{edit.updated_at}></template>
                    </p>
                    <h3 class="my_content_title">
                        <!-- <a target="downloadPdf" href="{{ URL::route('web.experiences.download',['t' => time()]) }}">
                            <span class="pdf_btn"><i class="fa fa-upload" aria-hidden="true"></i> PDF出力</span>
                        </a> -->
                        <!-- <span class="my_introduce" data-popup-target="my_introduce_btn"> -->
                        <span class="my_introduce" @click="levelPage('{{route('web.experiences.uploadVideo', ['upload' => 1])}}')">
                            <p><i class="fa fa-id-card-o" aria-hidden="true"></i>自己紹介映像(1分)</p>@if($experience && !empty($experience['video_url'])) <b>作成済</b>  @else <b>未作成</b> @endif
                        </span>

                        <div class="popup" tabindex="-3" role="dialog" data-popup-id="my_introduce_btn">
                            <div class="popup__container">
                                <div class="popup__content">
                                    <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                                        <h3><div class="popup__close"><i class="fa fa-times" aria-hidden="true"></i></div>自己紹介映像(1分)</h3>

                                        <div class="my_introduce_btn_box">
                                            <a @click="location.href='{{route('web.experiences.uploadVideo', ['upload' => 1])}}'" class="my_introduce_btn_a">映像ファイルのアップロード</a>
                                            <a @click="location.href='{{route('web.experiences.uploadVideo', ['upload' => 0])}}'" class="my_introduce_btn_b">ここで録画゙</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </h3>
                    <form id="testForm" action="" method="post" accept-charset="UTF-8" name="testForm">
                        <div class="my_content">
                            <ul>
                                <li class="my_item_title2">
                                    <div class="my_item_title_sub">氏名<span>＊</span></div>
                                    <div class="my_item_title_sub">ふりがな<span>＊</span></div>
                                </li>
                                <li class="my_item_input2">
                                    <div class="my_item_input_sub"><input type="text" class="input_text"
                                                                          v-model="edit.name" value=""
                                                                          name="name" maxlength="20" data-max="20"
                                                                          required/></div>
                                    <div class="my_item_input_sub"><input type="text" class="input_text"
                                                                          v-model="edit.name_kana" maxlength="20" value=""
                                                                          name="name_kana" data-max="20"
                                                                          required/></div>
                                </li>
                                <li class="my_item_title2">
                                    <div class="my_item_title_sub">生年月日<span>＊</span></div>
                                    <div class="my_item_title_sub">性別<span>＊</span></div>
                                </li>
                                <li class="my_item_input2">
                                    <div class="my_item_input_sub">
                                        <el-date-picker
                                                v-model="edit.birthday"
                                                type="date"
                                                name="birthday"
                                                class="input_text birthday"
                                                placeholder="「生年月日」はここで入力"
                                                default-value="{{date('Y-m-d', strtotime('-20 year'))}}"
                                                data-start-view="4" data-min-view="2"
                                                format="yyyy-MM-dd" value-format="yyyy-MM-dd" required>
                                        </el-date-picker>
                                    </div>
                                    <div class="my_item_input_radio2">
                                        <el-radio v-model="edit.sex" @change="changeValue('sex', $event)" name="sex" label=1 required>男性</el-radio>
                                        <el-radio v-model="edit.sex" @change="changeValue('sex', $event)" name="sex" label=2 required>女性</el-radio>
                                    </div>
                                </li>
                                <li class="my_item_title1">国籍(地域)<span>＊</span></li>
                                <li class="my_item_input2">
                                    <div class="select">
                                        <select id="select4" v-model="edit.nationality_id" name="nationality_id"
                                                required>
                                            <option value="" selected>選択する</option>
                                            @foreach(config("code.resume.nationality") as $k => $v)
                                                @if($k > 0)
                                                    <option value="{{$k}}">{{$v}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div v-if="edit.nationality_id == 17" class="input_box">
                                        <input type="text" v-model="edit.nationality" class="input_text"
                                               name="country_other"
                                               placeholder="「その他」はここで入力" required/>
                                    </div>
                                </li>
                                <li class="my_item_title1">現住所<span>＊</span></li>
                                <li class="my_item_input2">
                                    <div class="my_item_input_radio1">
                                        @foreach(config("code.resume.address2") as $k => $v)
                                            @if($k > 0)
                                                <span class="radio_item" style="margin-right: 15px;">
                                                    <el-radio v-model="edit.address" @change="changeYearTitle" name="address" class="radio3-text" label="{{$k}}" required>{{$v}}</el-radio>
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </li>
                                <div class="in_jp" style="display: block" v-if="edit.address!=2">
                                    <ul>
                                        <li class="my_item_title1">住所<span>＊</span></li>
                                        <li class="my_item_input2">
                                            <div class="select">
                                                <select id="in_jp_residence_select" v-model="edit.address_id"
                                                        name="in_jp_residence_select"
                                                        required>
                                                    <option value="" selected>選択する</option>
                                                    @foreach(config("code.resume.country_city") as $k => $v)
                                                        @if($k > 0)
                                                            <option value="{{$k}}">{{$v}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input_box">
                                                <input type="text" v-model="edit.address_other" class="input_text"
                                                       name="in_jp_address_other"
                                                       placeholder="「住所」はここで入力" required/>
                                            </div>
                                        </li>
                                        <li class="my_item_title1">ふりがな<span>＊</span></li>
                                        <li class="my_item_input1">
                                            <input type="text" v-model="edit.address_kana" class="input_text" value=""
                                                   name="in_jp_address_kana"
                                                   placeholder="全角カタカナを入力してください。"
                                                   data-max="100" maxlength="100" required/>
                                        </li>
                                        <li class="my_item_title2">
                                            <div class="my_item_title_sub">郵便番号<span>＊</span></div>
                                            <div class="my_item_title_sub">電話<span>＊</span></div>
                                        </li>
                                        <li class="my_item_input2">
                                            <div class="my_item_input_sub"><input type="text" v-model="edit.postal_code"
                                                                                  class="input_text"
                                                                                  value="" maxlength="7" name="postal_code"
                                                                                  data-max="7"
                                                                                  placeholder="「郵便番号」はここで入力"
                                                                                  required/></div>
                                            <div class="my_item_input_sub"><input type="text" v-model="edit.cell_phone"
                                                                                  class="input_text"
                                                                                  value="" maxlength="20" name="cell_phone"
                                                                                  data-max="20"
                                                                                  placeholder="「電話」はここで入力"
                                                                                  required/></div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="out_jp" style="display: block" v-if="edit.address==2">
                                    <ul>
                                        <li class="my_item_title1">住所<span>＊</span></li>
                                        <li class="my_item_input1">
                                            <input type="text" v-model="edit.address_other" class="input_text" value=""
                                                   name="out_jp_residence"
                                                   placeholder="「住所」はここで入力"
                                                   data-max="100" maxlength="100" required/>
                                        </li>
                                        <li class="my_item_title1">ふりがな<span>＊</span></li>
                                        <li class="my_item_input1">
                                            <input type="text" class="input_text" v-model="edit.address_kana"
                                                   name="out_jp_address_other"
                                                   placeholder="全角カタカナを入力してください。"
                                                   data-max="100" maxlength="100" required/>
                                        </li>
                                        <li class="my_item_title1">電話<span>＊</span>
                                        </li>
                                        <li class="my_item_input1">
                                            <div class="my_item_input_sub"><input type="text" v-model="edit.cell_phone"
                                                                                  class="input_text"
                                                                                  name="out_cell_phone"
                                                                                  placeholder="「電話」はここで入力"
                                                                                  data-max="20" maxlength="20" 
                                                                                  required/></div>
                                        </li>
                                    </ul>
                                </div>
                                <li class="my_item_title1">緊急連絡先</li>
                                <li class="my_item_input1">
									<span>
                                        <el-checkbox name="emergency_contact"  v-model="edit.emergency_contact>0" @change="changeEmergencyContactValue()" label="1">同上</el-checkbox>
                                    </span>
                                </li>
                                <div class="emergency_contact" style="display: block;" v-if="edit.emergency_contact<1">
                                    <li class="my_item_title1">住所<span>＊</span></li>
                                    <li class="my_item_input2">
                                        <div class="select">
                                            <select id="urgent_residence_select" v-model="edit.emergency_address_id"
                                                    name="urgent_residence_select"
                                                    required>
                                                <option value="" selected>選択してください</option>
                                                @foreach(config("code.resume.country_city") as $k => $v)
                                                    @if($k > 0)
                                                        <option value="{{$k}}">{{$v}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input_box">
                                            <input type="text" v-model="edit.emergency_address_other" class="input_text"
                                                   name="emergency_address_other"
                                                   placeholder="「その他」はここで入力" required/>
                                        </div>
                                    </li>
                                    <li class="my_item_title1">ふりがな<span>＊</span></li>
                                    <li class="my_item_input1">
                                        <input type="text" class="input_text" v-model="edit.emergency_address_kana"
                                               name="emergency_address_kana"
                                               data-max="100" maxlength="100" required/>
                                    </li>
                                    <li class="my_item_title2">
                                        <div class="my_item_title_sub">郵便番号<span>＊</span></div>
                                        <div class="my_item_title_sub">電話<span>＊</span></div>
                                    </li>
                                    <li class="my_item_input2">
                                        <div class="my_item_input_sub"><input type="text" class="input_text"
                                                                              v-model="edit.emergency_postal_code"
                                                                              name="emergency_postal_code" maxlength="7" data-max="7"
                                                                              placeholder="「郵便番号」はここで入力"
                                                                              required/></div>
                                        <div class="my_item_input_sub"><input type="text" class="input_text"
                                                                              v-model="edit.emergency_cell_phone"
                                                                              name="emergency_cell_phone" maxlength="20" data-max="20"
                                                                              placeholder="「電話」はここで入力"
                                                                              required/></div>
                                    </li>
                                </div>
                                <li class="my_item_title2">
                                    <div class="my_item_title_sub">最寄駅</div>
                                    <div class="my_item_title_sub">Eメール<span>＊</span></div>
                                </li>
                                <li class="my_item_input2">
                                    <div class="my_item_input_sub"><input type="text" class="input_text"
                                                                          v-model="edit.nearest_station"
                                                                          name="station_name" maxlength="20" data-max="20"/></div>
                                    <div class="my_item_input_sub"><input type="email" class="input_text"
                                                                          v-model="edit.email"
                                                                          name="email" required/></div>
                                </li>
                                <li class="my_item_title1">
                                    <div class="year_title">日本滞在年数<span>＊</span></div>
                                </li>
                                <li class="my_item_input2">
                                    <div class="select">
                                        <select id="year_select" @change="changeYearSelect()" v-model="edit.residence_in_japan_year"
                                                name="year_select" required>
                                            <option value="" selected>選択して下さい</option>
                                            @foreach(config("code.resume.residence_in_japan_year") as $k => $v)
                                                @if($k > 0)
                                                    <option value="{{$k}}">{{$v}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="select">
                                        <select id="month_select" :disabled="japan_month_disable" v-model="edit.residence_in_japan_month"
                                                name="month_select" required>
                                            <option value="" selected>選択して下さい</option>
                                            @foreach(config("code.resume.residence_in_japan_month") as $k => $v)
                                                @if($k > 0)
                                                    <option value="{{$k}}">{{$v}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                <li class="my_item_title1">顔写真</li>
                                <li class="my_item_input1">
                                    <div class="my_item_input_photo">
                                        <input type="hidden" v-model="edit.photo">
                                        <span><input type="text" class="input_text photo_file" disabled
                                               placeholder="縦横4cm×3cmを5MB以内。" v-model="showPhoto" name="photo_file"/>
                                            <a class="choose_upload_btn">選択 <i class="fa fa-chevron-down fi-arrow-down"></i></a>
                                        </span>
                                        <input type="button" @click="uploadPhoto" class="upload_btn" value="アップロード"/>
                                        <input type="file" name="attachment" accept="image/*" @change="onFileChange" class="upload_file my_photo" id="my_photo"/>
                                    </div>
                                    <div id="preview" ><img :src="edit.sphoto" class="preview"/></div>
                                </li>
                                <template v-if="edit.address!=2">
                                <li class="my_item_title1">在留資格<span>＊</span></li>
                                <li class="my_item_input2">
                                    <div class="select">
                                        <select id="select5" v-model="edit.visa_type" name="visa_select" required>
                                            <option value="" selected>選択して下さい</option>
                                            @foreach(config("code.resume.visa_type") as $k => $v)
                                                @if($k > 0)
                                                    <option value="{{$k}}">{{$v}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input_box" v-if="edit.visa_type==7">
                                        <input type="text" v-model="edit.visa_other" class="input_text"
                                               name="visa_other"
                                               placeholder="「その他」はここで入力" required/>
                                    </div>
                                </li>
                                <li class="my_item_title1">ビザ有効期限</li>
                                <li class="my_item_input1">
                                    <div class="my_item_input_sub">
                                        <el-date-picker
                                                v-model="edit.visa_term"
                                                type="date"
                                                name="visa_term"
                                                class="input_text"
                                                placeholder=""
                                                data-start-view="4" data-min-view="2"
                                                format="yyyy-MM-dd" value-format="yyyy-MM-dd" >
                                        </el-date-picker>
                                    </div>
                                </li>
                                </template>
                                <div class="education_box">
                                    <ul class="education_box_main">
                                        <li class="line_box">
                                            <p>学歴
                                                <b></b></p>
                                        </li>
                                        <li class="education_box_list" v-for="(item, i) in edit.academic_background"
                                            :id="'education_box_list' + (i+1)">
                                            <ul>
                                                <span v-if="edit.academic_background.length==i+1" :id="'add_'+edit.academic_background.length" class="education_add_btn" @click="addAcademicBackground"><i
                                                            class="fa fa-plus"></i></span>
                                                <span class="education_del_btn"
                                                      @click="delObjItem('academic_background',i)"><i
                                                            class="fa fa-minus"></i></span>
                                                <li class="my_item_title2">
                                                    <div class="my_item_title_sub">国・地域<span>＊</span></div>
                                                    <div class="my_item_title_sub">学校区分<span>＊</span></div>
                                                </li>
                                                <li class="my_item_input2">
                                                    <div class="select">
                                                        <select v-model="item.country" :id="'country_input'+i" :name="'country_input'+i"
                                                                required>
                                                            <option value="" selected>選択する</option>
                                                            <option value="日本">日本</option>
                                                            @foreach(config("code.resume.nationality") as $k => $v)
                                                                @if($k > 0 && $v != 'その他')
                                                                    <option value="{{$v}}">{{$v}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
{{--                                                        <input v-model="item.country"--}}
{{--                                                                                          type="text"--}}
{{--                                                                                          class="input_text country_input"--}}
{{--                                                                                          :id="'country_input'+i"--}}
{{--                                                                                          :name="'country_input'+i"--}}
{{--                                                                                          data-max="20" required/>--}}
                                                    </div>
                                                    <div class="select">
                                                        <select class="school_select" v-model="item.final_education"
                                                                :id="'school_select'+i"
                                                                :name="'school_select'+i" required>
                                                            <option value="" selected>選択してください</option>
                                                            @foreach(config("code.resume.final_education") as $k => $v)
                                                                @if($k > 0)
                                                                    <option value="{{$k}}">{{$v}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="my_item_title1">期間<span>＊</span></li>
                                                <li class="my_item_input2">
                                                    <div class="select_middle2">-</div>
                                                    <div class="my_item_input_sub">
                                                        <el-date-picker
                                                                :name="'enrol_date_start'+i"
                                                                v-model="item.enrol_date_start"
                                                                type="month"
                                                                class="input_text"
                                                                placeholder="入学年月"
                                                                data-start-view="4" data-min-view="2"
                                                                format="yyyy-MM" value-format="yyyy-MM-dd"  required="true">
                                                        </el-date-picker>
                                                    </div>
                                                    <div class="my_item_input_sub">
                                                        <el-date-picker
                                                                :name="'enrol_date_end'+i"
                                                                v-model="item.enrol_date_end"
                                                                type="month"
                                                                class="input_text"
                                                                placeholder="卒業または卒業見込み年月"
                                                                data-start-view="4" data-min-view="2"
                                                                format="yyyy-MM" value-format="yyyy-MM-dd"  required="true">
                                                        </el-date-picker>
                                                    </div>

                                                </li>
                                                <li class="my_item_title2">
                                                    <div class="my_item_title_sub">学校名<span>＊</span></div>
                                                    <div class="my_item_title_sub">学部名（研究科名）</div>
                                                </li>
                                                <li class="my_item_input2">
                                                    <div class="my_item_input_sub"><input type="text"
                                                                                          v-model="item.school_name"
                                                                                          :id="'school_name'+i"
                                                                                          :name="'school_name'+i"
                                                                                          class="input_text school_name"
                                                                                          data-max="50" maxlength="50"  required/></div>
                                                    <div class="my_item_input_sub"><input type="text"
                                                                                          v-model="item.department_name"
                                                                                          :id="'department_name'+i"
                                                                                          :name="'department_name'+i"
                                                                                          class="input_text department_name"
                                                                                          data-max="50" maxlength="50" /></div>
                                                </li>
                                                <li class="my_item_title2">
                                                    <div class="my_item_title_sub">学科名（専攻名）</div>
                                                    <!-- <div class="my_item_title_sub">文理区分<span>＊</span></div> -->
                                                </li>
                                                <li class="my_item_input2">
                                                    <div class="my_item_input_sub"><input type="text" maxlength="50" 
                                                                                          class="input_text subject_name"
                                                                                          v-model="item.subject_name"
                                                                                          :id="'subject_name'+i"
                                                                                          :name="'subject_name'+i"
                                                                                          data-max="50"/></div>
                                                    <!-- <div class="select">
                                                        <select class="wenli_select" v-model="item.science_art"
                                                                :id="'wenli_select'+i"
                                                                :name="'wenli_select'+i" required>
                                                            <option disabled value="" selected>選択してください</option>
                                                            @foreach(config("code.resume.science_arts") as $k => $v)
                                                                @if($k > 0)
                                                                    <option value="{{$k}}">{{$v}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div> -->
                                                </li>
                                                <span v-if="edit.academic_background.length==i+1" :id="'add_'+edit.academic_background.length" class="education_add_btn btn_bottom_add" @click="addAcademicBackground"><i
                                                            class="fa fa-plus"></i></span>
                                                <span class="education_del_btn btn_bottom_del"
                                                      @click="delObjItem('academic_background',i)"><i
                                                            class="fa fa-minus"></i></span>
                                            </ul>
                                        </li>
                                    </ul>
                                    <div></div>
                                </div>
                                <div class="certificate_box">
                                    <ul class="certificate_box_main">
                                        <li class="line_box">
                                            <p><span v-if="edit.is_qualification_and_license > 0" :id="'add_'+edit.qualification_and_license.length"
                                                     class="certificate_add_btn" style="display: block"
                                                     @click="addQualificationAndLicense"><i
                                                            class="fa fa-plus"></i></span>資格・免許
                                                <b></b></p>
                                        </li>
                                        <li class="my_item_input2">

                                            <div class="my_item_input_radio2">
                                                @foreach(config("code.resume.have_license") as $k => $v)
                                                    <el-radio v-model="edit.is_qualification_and_license" name="certificate_no_have" label="{{$k}}" required>{{$v}}</el-radio>
                                                @endforeach
                                            </div>
                                        </li>
                                        <li v-if="edit.is_qualification_and_license > 0"
                                            v-for="(item, i) in edit.qualification_and_license"
                                            class="certificate_box_list" style="display: block"
                                            :id="'certificate_box_list'+(i+1)">
                                            <ul>
                                                <h5>資格・免許【<b><{i+1}></b>】</h5>
                                                <li class="my_item_title1">
                                                    <div class="my_item_title_sub certificate_name">
                                                        <span
                                                                @click="delObjItem('qualification_and_license', i)"
                                                                class="certificate_del_btn"><i class="fa fa-minus"></i></span>名称<b></b>
                                                    </div>
                                                </li>
                                                <li class="my_item_input2">
                                                    <div class="select">
                                                        <select class="certificate_select" v-model="item.name"
                                                                :id="'certificate_select'+i"
                                                                :name="'certificate_select'+i"
                                                                name="certificate_select1">
                                                            <option value="" selected>選択してください</option>
                                                            @foreach(config("code.resume.license_names") as $k => $v)
                                                                @if($k > 0)
                                                                    <option value="{{$k}}">{{$v}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="select certificate_level_item" v-if="item.name==1||item.name<1">
                                                        <select class="certificate_level" v-model="item.level"
                                                                :id="'certificate_level'+i"
                                                                :name="'certificate_level'+i">
                                                            <option value="" selected>選択してください</option>
                                                            @foreach(config("code.resume.license_jlpt") as $k => $v)
                                                                @if($k > 0)
                                                                    <option value="{{$k}}">{{$v}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="my_item_input_sub certificate_other_item" style="display: block" v-if="item.name>1&&item.name < 5">
                                                        <input max="999"  type="number" class="input_text certificate_point" style="width: 80%"
                                                                v-model="item.point"
                                                                :id="'certificate_point'+i"
                                                                :name="'certificate_point'+i"
                                                                placeholder=""
                                                                />点
                                                    </div>
                                                    <div v-if="item.name==5"
                                                         class="my_item_input_sub certificate_other_item"
                                                         style="display: block"><input
                                                                type="text" class="input_text certificate_other"
                                                                v-model="item.certificate_other"
                                                                :id="'certificate_other'+i"
                                                                :name="'certificate_other'+i" maxlenght="50" data-max="50"
                                                                placeholder="「その他」はここで入力"
                                                                /></div>
                                                </li>
                                                <li class="my_item_title1">取得年月</li>
                                                <li class="my_item_input1">
                                                    <div class="my_item_input_sub">
                                                        <el-date-picker
                                                                :name="'certificate_date'+i"
                                                                v-model="item.certificate_date"
                                                                type="month"
                                                                class="input_text"
                                                                placeholder=""
                                                                data-start-view="4" data-min-view="2"
                                                                format="yyyy-MM" value-format="yyyy-MM-dd">
                                                        </el-date-picker>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <div></div>
                                </div>
                                <div class="language_box">
                                    <ul class="language_box_main">
                                        <li class="line_box">
                                            <p><span :id="'add_'+edit.language_skill.length"  class="language_add_btn" @click="addLanguageSkill"><i
                                                            class="fa fa-plus"></i></span>語学スキル
                                                <b></b></p>
                                        </li>

                                        <li class="language_box_list" id="language_box_list1">
                                            <ul>
                                                <li class="my_item_title2">
                                                    <div class="my_item_title_sub language_jp">日本語<b></b><span>＊</span>
                                                    </div>
                                                    <div class="my_item_title_sub language_en">
                                                        英語<b></b>
                                                    </div>
                                                </li>
                                                <li class="my_item_input2">
                                                    <div class="select language_jp">
                                                        <select class="language_jp_select" v-model="edit.jp_level"
                                                                :id="'language_jp_select'"
                                                                :name="'language_jp_select'" required>
                                                            <option value="" selected>選択してください</option>
                                                            @foreach(config("code.resume.jp_level") as $k => $v)
                                                                @if($k > 0)
                                                                    <option value="{{$k}}">{{$v}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="select language_en">
                                                        <select class="language_en_select" v-model="edit.en_level"
                                                                :id="'language_en_select'"
                                                                :name="'language_en_select'">
                                                            <option value="0" selected>選択してください</option>
                                                            @foreach(config("code.resume.en_level") as $k => $v)
                                                                @if($k > 0)
                                                                    <option value="{{$k}}">{{$v}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </li>
                                                <div v-for="(item, i) in edit.language_skill" :id="'language_other_box'+(i+1)" class="language_other_box">
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub">
                                                            <span class="language_del_btn" @click="delObjItem('language_skill', i)"><i class="fa fa-minus"></i></span>
                                                            語学スキル【<b><{i+3}></b>】<b></b></div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="my_item_input_sub language_other_item"><input
                                                                    type="text"
                                                                    class="input_text language_other_text"
                                                                    v-model="item.other_text"
                                                                    :id="'language_other_text'+i"
                                                                    :name="'language_other_text'+i"
                                                                    data-max="100"
                                                                    placeholder="中国語"
                                                                    /></div>
                                                        <div class="select">
                                                            <select class="language_other_select"
                                                                    v-model="item.other_level"
                                                                    :id="'language_other_select'+i"
                                                                    :name="'language_other_select'+i"
                                                                    >
                                                                <option selected>選択してください</option>
                                                                @foreach(config("code.resume.other_level") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </li>
                                                </div>
                                            </ul>
                                        </li>
                                    </ul>
                                    <div></div>
                                </div>
                                <div class="it_box">
                                    <ul class="it_box_main">
                                        <li class="line_box">
                                            <p>PC/ITスキル
                                                <b></b></p>
                                        </li>
                                        <li class="my_item_title2">
                                            <div class="my_item_title_sub certificate_name">OS<b></b>
                                            </div>
                                            <div class="my_item_title_sub certificate_name">Office<b></b>
                                            </div>
                                        </li>
                                        <li class="my_item_input2">
                                            <div class="my_item_input_sub it_checkbox_list1">
                                                @foreach(config("code.resume.it_skill_os") as $k => $v)
                                                    @if($k > 0)
                                                        <el-checkbox name="it_skill_os" v-model="edit.it_skill_os" label="{{$k}}">{{$v}}</el-checkbox>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="my_item_input_sub it_checkbox_list1">
                                                @foreach(config("code.resume.it_skill_office") as $k => $v)
                                                    @if($k > 0)
                                                        <el-checkbox name="it_skill_office" v-model="edit.it_skill_office" label="{{$k}}">{{$v}}</el-checkbox>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </li>
                                        <template v-for="(item, i) in edit.it_skill_graphic">
                                            <li class="it_box_list" :id="'it_box_list'+(i+1)">
                                                <ul>
                                                    <span v-if="edit.it_skill_graphic.length==i+1"  style="display: block" :id="'add_'+(i+1)" class="it_add_btn" @click="addItSkill('it_skill_graphic')"><i
                                                                class="fa fa-plus"></i></span>
                                                    <span class="it_del_btn" @click="delObjItem('it_skill_graphic', i)"><i
                                                                class="fa fa-minus"></i></span>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub soft_name">
                                                            デザイン(2D/3D)【<{i+1}>】<b></b>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="soft_select" v-model="item.name"
                                                                    :id="'soft_select'+i" :name="'soft_select'+i"
                                                                    >
                                                                <option value="" selected>選択して下さい</option>
                                                                @foreach(config("code.resume.it_skill_graphic") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div v-if="item.name=='その他'"
                                                             class="my_item_input_sub soft_other_item"><input
                                                                    type="text"
                                                                    class="input_text soft_other_text"
                                                                    v-model="item.other"
                                                                    :id="'soft_other_text'+i"
                                                                    :name="'soft_other_text'+i"
                                                                    data-max="50"
                                                                    placeholder="「その他」はここで入力"/>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub ">使用経験<b></b></div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select @change="changeYear('it_skill_graphic', i)" class="soft_year_select" v-model="item.year"
                                                                    :id="'soft_year_select'+i"
                                                                    :name="'soft_year_select'+i">
                                                                <option value="" selected>年</option>
                                                                @foreach(config("code.resume.residence_in_japan_year") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="select">
                                                            <select class="soft_month_select" v-model="item.month" :disabled="item.year==11"
                                                                    :id="'soft_month_select1'+i"
                                                                    :name="'soft_month_select1'+i">
                                                                <option value="" selected>月</option>
                                                                @foreach(config("code.resume.residence_in_japan_month") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </template>
                                        </li>
                                    </ul>
                                </div>
                                <div class="it_box">
                                    <ul class="it_box_main">
                                        <template v-for="(item, i) in edit.it_skill_language">
                                            <li class="it_box_list" :id="'it_box_list'+(i+1)">
                                                <ul>
                                                 <span v-if="edit.it_skill_language.length==i+1" style="display: block" :id="'add_'+(i+1)" class="it_add_btn" @click="addItSkill('it_skill_language')"><i
                                                    class="fa fa-plus"></i></span>
                                                    <span  class="it_del_btn"
                                                          @click="delObjItem('it_skill_language', i)"><i
                                                                class="fa fa-minus"></i></span>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub soft_name">
                                                            開発言語【<{i+1}>】<b></b>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="soft_select" v-model="item.name"
                                                                    :id="'language_soft_select'+i" :name="'language_soft_select'+i"
                                                                    >
                                                                <option value="" selected>選択して下さい</option>
                                                                @foreach(config("code.resume.it_skill_language") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div v-if="item.name=='その他'"
                                                             class="my_item_input_sub soft_other_item"><input
                                                                    type="text"
                                                                    class="input_text soft_other_text"
                                                                    v-model="item.other"
                                                                    :id="'language_soft_other_text'+i"
                                                                    :name="'language_soft_other_text'+i"
                                                                    data-max="50"
                                                                    placeholder="「その他」はここで入力"
                                                                     maxlength="50" />
                                                        </div>
                                                    </li>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub ">使用経験<b></b></div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="soft_year_select" v-model="item.year"
                                                                    @change="changeYear('it_skill_language', i)"
                                                                    :id="'language_soft_year_select'+i"
                                                                    :name="'language_soft_year_select'+i">
                                                                <option value="" selected>年</option>
                                                                @foreach(config("code.resume.residence_in_japan_year") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="select">
                                                            <select class="soft_month_select" v-model="item.month"
                                                                    :disabled="item.year==11"
                                                                    :id="'language_soft_month_select1'+i"
                                                                    :name="'language_soft_month_select1'+i">
                                                                <option value="" selected>月</option>
                                                                @foreach(config("code.resume.residence_in_japan_month") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                <div class="it_box">
                                    <ul class="it_box_main">
                                        <template v-for="(item, i) in edit.it_skill_db">
                                            <li class="it_box_list" :id="'it_box_list'+(i+1)">
                                                <ul>
                                                    <span v-if="edit.it_skill_db.length==i+1" style="display: block" :id="'add_'+(i+1)" class="it_add_btn" @click="addItSkill('it_skill_db')"><i
                                                    class="fa fa-plus"></i></span>
                                                    <span class="it_del_btn" @click="delObjItem('it_skill_db', i)"><i
                                                                class="fa fa-minus"></i></span>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub soft_name">
                                                            DB【<{i+1}>】<b></b>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="soft_select" v-model="item.name"
                                                                    :id="'db_select'+i" :name="'db_select'+i"
                                                                    >
                                                                <option value="" selected>選択して下さい</option>
                                                                @foreach(config("code.resume.it_skill_db") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div v-if="item.name=='その他'"
                                                             class="my_item_input_sub soft_other_item"><input
                                                                    type="text"
                                                                    class="input_text soft_other_text"
                                                                    v-model="item.other"
                                                                    :id="'db_other_text'+i"
                                                                    :name="'db_other_text'+i"
                                                                    data-max="50"
                                                                    placeholder="「その他」はここで入力"
                                                                    />
                                                        </div>
                                                    </li>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub ">使用経験<b></b></div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="soft_year_select" v-model="item.year"
                                                                    :id="'db_year_select'+i"
                                                                    @change="changeYear('it_skill_db', i)"
                                                                    :name="'db_year_select'+i">
                                                                <option value="" selected>年</option>
                                                                @foreach(config("code.resume.residence_in_japan_year") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="select">
                                                            <select class="soft_month_select" v-model="item.month"
                                                                    :disabled="item.year==11"
                                                                    :id="'db_month_select'+i"
                                                                    :name="'db_month_select'+i">
                                                                <option value="" selected>月</option>
                                                                @foreach(config("code.resume.residence_in_japan_month") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                <div class="it_box">
                                    <ul class="it_box_main">
                                        <template v-for="(item, i) in edit.it_skill_framework">
                                            <li class="it_box_list" :id="'it_box_list'+(i+1)">
                                                <ul>
                                                    <span v-if="edit.it_skill_framework.length==i+1" style="display: block" :id="'add_'+(i+1)" class="it_add_btn" @click="addItSkill('it_skill_framework')"><i
                                                    class="fa fa-plus"></i></span>
                                                    <span class="it_del_btn"
                                                          @click="delObjItem('it_skill_framework', i)"><i
                                                                class="fa fa-minus"></i></span>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub soft_name">
                                                            フレームワーク【<{i+1}>】<b></b>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="soft_select" v-model="item.name"
                                                                    :id="'framework_soft_select'+i" :name="'framework_soft_select'+i"
                                                                    >
                                                                <option value="" selected>選択して下さい</option>
                                                                @foreach(config("code.resume.it_skill_framework") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div v-if="item.name=='その他'"
                                                             class="my_item_input_sub soft_other_item"><input
                                                                    type="text"
                                                                    class="input_text soft_other_text"
                                                                    v-model="item.other"
                                                                    :id="'framework_soft_other_text'+i"
                                                                    :name="'framework_soft_other_text'+i"
                                                                    data-max="50"
                                                                    placeholder="「その他」はここで入力"
                                                                    />
                                                        </div>
                                                    </li>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub ">使用経験<b></b></div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="soft_year_select" v-model="item.year"
                                                                    :id="'framework_soft_year_select'+i"
                                                                    @change="changeYear('it_skill_framework', i)"
                                                                    :name="'framework_soft_year_select'+i" >
                                                                <option value="" selected>年</option>
                                                                @foreach(config("code.resume.residence_in_japan_year") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="select">
                                                            <select class="soft_month_select" v-model="item.month" :disabled="item.year==11"
                                                                    :id="'framework_soft_month_select1'+i"
                                                                    :name="'framework_soft_month_select1'+i">
                                                                <option value="" selected>月</option>
                                                                @foreach(config("code.resume.residence_in_japan_month") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                <li class="my_item_title2">
                                    <div class="my_item_title_sub ">通勤時間<b></b><span>＊</span></div>
                                    <div class="my_item_title_sub ">扶養家族（配偶者を除く）<b></b><span>＊</span></div>
                                </li>
                                <li class="my_item_input2">
                                    <div class="select">
                                        <select class="frame_year_select" v-model="edit.commuting_hours"
                                                id="commuting_hours"
                                                name="commuting_hours" required>
                                            <option value="" selected>時間以内希望</option>
                                            @foreach(config("code.resume.commuting_hours") as $k => $v)
                                                @if($k > 0)
                                                    <option value="{{$k}}" selected>{{$v}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="select">
                                        <select class="frame_month_select" v-model="edit.family_members_num"
                                                id="family_members_num"
                                                name="family_members_num" required>
                                            <option value="" selected>人</option>
                                            @foreach(config("code.resume.family_members_num") as $k => $v)
                                                @if($k > 0)
                                                    <option value="{{$k}}" selected>{{$v}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                <li class="my_item_title2">
                                    <div class="my_item_title_sub ">配偶者<b></b><span>＊</span></div>
                                    <div class="my_item_title_sub ">配偶者の扶養義務<b></b><span>＊</span></div>
                                </li>
                                <li class="my_item_input2">
                                    <div class="my_item_input_radio2">
                                        @foreach(config("code.resume.is_spouse") as $k => $v)
                                            <el-radio name="is_spouse" v-model="edit.is_spouse" label="{{$k}}">{{$v}}</el-radio>
                                        @endforeach
                                    </div>
                                    <div class="my_item_input_radio2">
                                        @foreach(config("code.resume.is_spouse_support") as $k => $v)
                                            <el-radio name="is_spouse_support" v-model="edit.is_spouse_support" label="{{$k}}">{{$v}}</el-radio>
                                        @endforeach
                                    </div>
                                </li>
                                <li class="my_item_title1">
                                    <div class="my_item_title_sub">希望勤務地<b></b><span>＊</span></div>
                                </li>
                                <li class="my_item_input2">
                                    <div class="my_item_input_sub it_checkbox_list1">
                                        @foreach(config("code.resume.desired_place") as $k => $v)
                                            @if($k > 0)
                                                @if($k !=3)
                                                    <el-checkbox name="desired_place" v-if="hideDesiredPlace" disabled="hideDesiredPlace" v-model="edit.desired_place" @change="handleCheckedDesiredPlace($event, {{$k}})" label="{{$k}}">{{$v}}</el-checkbox>
                                                    <el-checkbox name="desired_place" v-else v-model="edit.desired_place" @change="handleCheckedDesiredPlace($event, {{$k}})" label="{{$k}}">{{$v}}</el-checkbox>
                                                    @else
                                                 <el-checkbox name="desired_place" v-model="edit.desired_place" @change="handleCheckedDesiredPlace($event, {{$k}})" label="{{$k}}">{{$v}}</el-checkbox>
                                                @endif

                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="select">
                                        <select class="frame_month_select" v-if="hideDesiredPlace" disabled v-model="edit.desired_place_ids"
                                                id="desired_place_ids"
                                                name="desired_place_ids">
                                            <option disabled value="" selected>選択して下さい</option>
                                        </select>
                                        <select class="frame_month_select" v-else v-model="edit.desired_place_ids"
                                                id="desired_place_ids"
                                                name="desired_place_ids">
                                            <option value="" selected>選択して下さい</option>
                                            @foreach(config("code.resume.country_city") as $k => $v)
                                                @if($k > 2)
                                                    <option value="{{$k}}">{{$v}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                <li class="my_item_title1">
                                    <div class="my_item_title_sub ">志望の動機、自己PR、趣味、特技など<b></b><span>＊</span></div>
                                </li>
                                <li><textarea class="text_area" v-model="edit.pr_other" id="introduce_text"
                                              name="pr_other"
                                              rows="5" required></textarea></li>
                                <li class="my_item_title1">
                                    <div class="my_item_title_sub ">
                                        本人希望記入欄（特に待遇・職種・勤務時間・その他についての希望などがあれば記入）<b></b></div>
                                </li>
                                <li><textarea class="text_area" v-model="edit.other_expected_types" id="hope_text"
                                              name="other_expected_types" rows="5"></textarea>
                                </li>
                                <li class="my_item_title1">
                                    <div class="my_item_title_sub ">職歴</div>
                                </li>
                                <li class="my_item_input2">
                                <li class="my_item_input2">
{{--                                    is_experience --}}
                                    <div class="my_item_input_radio2">
                                        @foreach(config("code.resume.is_experience") as $k => $v)
                                            <el-radio name="is_experience" @change="experienceChange"  v-model="edit.is_experience" label="{{$k}}">{{$v}}</el-radio>
                                        @endforeach
                                    </div>
                                </li>
                                <div class="experience_body" style="display: block" v-if="edit.is_experience>0">
                                    <ul class="job_summary_item">
                                        <li class="my_item_title1">
                                            <div class="my_item_title_sub">
                                                <span class="example_text_btn"></span>職務要約<a  @click="pop1=!pop1" class="zebra_tips1" title="1、長期勤続によるキャリアの形成。</br> 2、技能・ノウ ハウの継承。
 "><i class="fa fa-pencil" aria-hidden="true"></i> 入力例</a></div>
                                        </li>
                                        <li><textarea v-model="edit.job_summary" class="text_area" id="job_summary" name="job_summary"
                                                      rows="5"></textarea></li>
                                    </ul>

                                    <div class="experience_box">
                                        <ul class="experience_box_main">

                                            <li class="experience_box_list" v-for="(item,i) in edit.experiences" :id="'experience_box_list' + (i+1)">
                                                <h5> 職歴詳細【<b><{i+1}></b>】</h5>
                                                <ul>
                                                    <span v-if="(i+1) == edit.experiences.length||i==0" :id="'add_'+(i+1)" style="display: block;" class="experience_add_btn" @click="addExperience()"><i class="fa fa-plus"></i></span>
                                                    <span class="experience_del_btn" @click="delObjItem('experiences', i)"><i class="fa fa-minus"></i></span>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub">国・地域<span>＊</span></div>
                                                    </li>
                                                    <li class="my_item_input1">
                                                        <div class="select">
                                                            <select v-model="item.country" :id="'experience_country_input'+i" :name="'experience_country_input'+i"
                                                                    required>
                                                                <option value="" selected>選択する</option>
                                                                <option value="日本">日本</option>
                                                                @foreach(config("code.resume.nationality") as $k => $v)
                                                                    @if($k > 0 && $v != 'その他')
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_title1">期間<span>＊</span></li>
                                                    <li class="my_item_input2">
                                                        <div class="my_item_input_sub">
                                                            <el-date-picker
                                                                    :name="'start_date'+i"
                                                                    v-model="item.start_date"
                                                                    type="month"
                                                                    class="input_text"
                                                                    placeholder="入社年月"
                                                                    data-start-view="4" data-min-view="2"
                                                                    format="yyyy-MM" value-format="yyyy-MM-dd"  required>
                                                            </el-date-picker>
                                                        </div>
                                                        <div class="select_middle3">-</div>
                                                        <div class="my_item_input_sub">
                                                            <el-date-picker
                                                                    :name="'end_date'+i"
                                                                    :disabled="item.is_now > 0"
                                                                    v-model="item.end_date"
                                                                    type="month"
                                                                    class="input_text"
                                                                    placeholder="退社年月"
                                                                    data-start-view="4" data-min-view="2"
                                                                    format="yyyy-MM" value-format="yyyy-MM-dd" required>
                                                            </el-date-picker>
                                                        </div>
                                                        <div class="my_item_input_sub experience_so_far">
															<span>
                                                                <el-checkbox :name="'experience_is_now'+i" v-model="item.is_now=='1'" @change="changeIsNow(i)" label="1">現在に至る</el-checkbox>
                                                            </span>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_title2">
                                                        <div class="my_item_title_sub">会社名<span>＊</span></div>
                                                        <div class="my_item_title_sub">部署/役職</div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="my_item_input_sub"><input type="text"
                                                                                              class="input_text corporate_name"
                                                                                              v-model="item.corporate_name"
                                                                                              :id="'experience_corporate_name'+i"
                                                                                              :name="'experience_corporate_name'+i"
                                                                                              data-max="100" required/>
                                                        </div>
                                                        <div class="my_item_input_sub"><input type="text"
                                                                                              class="input_text post_name"
                                                                                              v-model="item.post_name"
                                                                                              :id="'experience_post_name'+i"
                                                                                              :name="'experience_post_name'+i"
                                                                                              data-max="100"/>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub">業種</div>

                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="industry_select"
                                                                    v-model="item.industry_name"
                                                                    :id="'experience_industry_select'+i"
                                                                    :name="'experience_industry_select'+i">
                                                                <option value="">選択してください</option>
                                                                @foreach(config("code.resume.desired_fileds") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                                <option value="その他">その他</option>
                                                            </select>
                                                        </div>
                                                        <div class="my_item_input_sub" v-if="item.industry_name=='その他'"><input type="text"
                                                                                              class="input_text industry_other_name"
                                                                                              v-model="item.industry_other"
                                                                                              :id="'experience_industry_other'+i"
                                                                                              :name="'experience_industry_other'+i"
                                                                                              data-max="50"
                                                                                              placeholder="その他はここで入力"
                                                                                              required/></div>
                                                    </li>
                                                    <li class="my_item_title2">
                                                        <div class="my_item_title_sub">従業員数</div>
                                                        <div class="my_item_title_sub">雇用形態<span>＊</span></div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="employ_class employees_num"
                                                                    v-model="item.employees_num"
                                                                    :id="'experience_employ_type'+i"
                                                                    :name="'experience_employ_type'+i">
                                                                <option value="" selected>選択してください</option>
                                                                @foreach(config("code.company.member_total") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
{{--                                                            <input max="500000" type="number"--}}
{{--                                                                  class="input_text employees_num"--}}
{{--                                                                  v-model="item.employees_num"--}}
{{--                                                                  :id="'experience_employees_num'+i"--}}
{{--                                                                  :name="'experience_employees_num'+i"--}}
{{--                                                                  placeholder="数字(1-50万)"--}}
{{--                                                                  />--}}
                                                        </div>
                                                        <div class="select">
                                                            <select class="employ_class"
                                                                    v-model="item.employ_type"
                                                                    :id="'experience_employ_type'+i"
                                                                    :name="'experience_employ_type'+i"
                                                                    required>
                                                                <option value="" selected>選択してください</option>
                                                                @foreach(config("code.resume.employ_types") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub">職種</div>
                                                    </li>
                                                    <li class="my_item_input2">
                                                        <div class="select">
                                                            <select class="occupation_select"
                                                                    v-model="item.occupation"
                                                                    :id="'experience_occupation_select'+i"
                                                                    :name="'experience_occupation_select'+i"
                                                                    >
                                                                <option value="" selected>選択してください</option>
                                                                @foreach(config("code.resume.desired_job_type") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                                <option value="その他">その他</option>
                                                            </select>
                                                        </div>
                                                        <div class="my_item_input_sub" v-if="item.occupation=='その他'">
                                                            <input type="text" class="input_text occupation_other_name"
                                                                   v-model="item.occupation_other"
                                                                   :id="'experience_occupation_other'+i"
                                                                   :name="'experience_occupation_other'+i"
                                                                   data-max="50"
                                                                   placeholder="その他はここで入力"
                                                                   required/>
                                                        </div>
                                                    </li>
                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub">年収<span>＊</span></div>
                                                    </li>
                                                    <li class="my_item_input1">
                                                        <div class="select">
                                                            <select class="income_select"
                                                                             v-model="item.annual_income"
                                                                             :id="'experience_annual_income'+i"
                                                                             :name="'experience_annual_income'+i"
                                                                             required>
                                                                <option value="" selected>選択してください</option>
                                                                @foreach(config("code.resume.annual_income") as $k => $v)
                                                                    @if($k > 0)
                                                                        <option value="{{$v}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </li>

                                                    <li class="my_item_title1">
                                                        <div class="my_item_title_sub ">担当業務<b></b><span>＊</span></div>
                                                    </li>
                                                    <li><textarea class="text_area" required v-model="item.undertake_business" :id="'experience_undertake_business'+i"
                                                                  :name="'experience_undertake_business'+i" rows="5"></textarea></li>
                                                    <span v-if="(i+1) == edit.experiences.length||i==0" :id="'add_'+(i+1)" style="display: block;" class="experience_add_btn btn_bottom_add" @click="addExperience()"><i class="fa fa-plus"></i></span>
                                                    <span class="experience_del_btn btn_bottom_del" @click="delObjItem('experiences', i)"><i class="fa fa-minus"></i></span>
                                                </ul>
                                            </li>
                                        </ul>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="step_pages_end">
                                    <input type="submit" id="saveData" class="resume_ok_btn" value="保存">
                                </div>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="popup" :class="{'opened': pop1}" tabindex="-3" role="dialog" data-popup-id="job_summary_example" v-if="pop1">
            <div class="popup__container">
                <div class="popup__close" @click="pop1=false"><span></span><span></span></div>
                <div class="popup__content">
                    <div class="pop_content" role="dialog" data-popup-id="demoPopup">
                        <h3>職務要約例文 </h3>
                        <div class="job_summary_content_text">
                            大学卒業後、人材派遣の営業職として、中小～大手クライアント向け提案営業に従事してきました。新規開拓からはじまり既存顧客への実績拡大のための深耕営業も経験しております。現在はマネージャーとして、メンバー・業績マネジメントを行っております。
                            <div class="pop_btn">
                                <input type="button" value="確定"  @click="pop1=false" class="pop_ok_btn"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var base = {
            url: {
                create: "{{URL::route('web.experiences.store')}}", //save
                upload: "{{ URL::route('upload.save',['object' => 'photo']) }}",
                download:"{{ URL::route('web.experiences.download',['t' => time()]) }}"
            },
            today:"{{ date('Y-m') }}",
            item: @if($experience){!! json_encode($experience) !!}@else null @endif
        };
    </script>
    <script type="text/javascript" src="{{asset('js/jquery.autoTextarea.js')}}"></script>
    <script src="{{asset('/js/new/vuePage/myExperience.js')}}?v={{ date('YmdHis') }}"></script>
@endsection