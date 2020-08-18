@extends('layouts.admin')
@section('content')
    <style>

        .table-striped tbody > tr:nth-child(2n+1) > td {
            background-color: #f9f9f9;
        }

    </style>
    <table class="layui-table table-striped">
        <tbody>
        <tr>
            <td width="160">ID</td>
            <td>{{ $data->id }}</td>
        </tr>
        <tr>
            <td>氏名</td>
            <td>{{ $data->name }}</td>
        </tr>
        <tr>
            <td>ふりがな</td>
            <td>{{ $data->name_kana }}</td>
        </tr>
        <tr>
            <td>生年月日</td>
            <td>{{ $data->birthday }}</td>
        </tr>
        <tr>
            <td>性別</td>
            <td>{{ $data->sex }}</td>
        </tr>
        <tr>
            <td>国籍</td>
            <td> {{ $data->nationality }} </td>
        </tr>
        <tr>
            <td>現住所</td>
            <td>{{$data->address_str}}</td>
        </tr>
        @if ($data->address == 1)
            <tr>
                <td>住所</td>
                <td>{{$resumeInfo['country_city'][$data->address_id]}} {{$data->address_other}}</td>
            </tr>
        @else
            <tr>
                <td>住所</td>
                <td>{{$data->address_other}}</td>
            </tr>
        @endif
        <tr>
            <td>ふりがな</td>
            <td>{{$data->address_kana}}</td>
        </tr>
        @if ($data->address == 1)
            <tr>
                <td>郵便番号</td>
                <td>{{$data->postal_code}}</td>
            </tr>
        @endif
        <tr>
            <td>電話</td>
            <td>{{$data->cell_phone}}</td>
        </tr>
        @if($data['emergency_contact'] < 1)
            <tr>
                <td>緊急連絡先</td>
                <td><table class="layui-table table-striped">
                        <tr>
                            <td>住所</td>
                            <td>{{$data->emergency_contact_address}} {{$data->emergency_address_other}}</td>
                        </tr>
                        <tr>
                            <td>ふりがな</td>
                            <td>{{$data->emergency_address_kana}}</td>
                        </tr>
                        <tr>
                            <td>郵便番号</td>
                            <td>{{$data->emergency_postal_code}}</td>
                        </tr>
                        <tr>
                            <td>電話</td>
                            <td>{{$data->emergency_cell_phone}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endif

        <tr>
            <td>最寄駅</td>
            <td>{{$data->nearest_station}}</td>
        </tr>
        <tr>
            <td>Eメール</td>
            <td>{{$data->email}}</td>
        </tr>
        <tr>
            <td>最寄駅</td>
            <td>{{$data->nearest_station}}</td>
        </tr>
        <tr>
            <td>来日年数</td>
            <td>{{$data->japan_year}}</td>
        </tr>
        @if($data->photo)
        <tr>
            <td>顔写真</td>
            <td><img src="{{$data->photo}}"></td>
        </tr>
        @endif
        <tr>
            <td>ビザ種類</td>
            <td>{{$data->visa_type_str}}</td>
        </tr>
        <tr>
            <td>ビザ有効期限</td>
            <td>{{$data->visa_term}}</td>
        </tr>

        @if(count($data['academic_background']) > 0)
            <tr>
                <td>学歴</td>
                <td><table class="layui-table table-striped">
                        @foreach($data['academic_background'] as $item)
                        <tr>
                            <td>国籍・地域</td>
                            <td>{{$item['country']}} </td>
                        </tr>
                        <tr>
                            <td>学校区分</td>
                            <td>{{$resumeInfo['final_education'][$item['final_education']]}}</td>
                        </tr>
                        <tr>
                            <td>入学期間</td>
                            <td>{{$item['enrol_date_start']}} ~ {{$item['enrol_date_end']}}</td>
                        </tr>
                        <tr>
                            <td>学校名</td>
                            <td>{{$item['school_name']}}</td>
                        </tr>
                            <tr>
                                <td>学部名（研究科名）</td>
                                <td>{{$item['department_name']}}</td>
                            </tr>
                            <tr>
                                <td>学科名（専攻名）</td>
                                <td>{{$item['subject_name']}}</td>
                            </tr>
                            <tr>
                                <td>文理区分</td>
                                <td>{{$resumeInfo['science_arts'][$item['science_art']]}}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endif
        @if($data->is_qualification_and_license)
            <tr>
                <td>資格・免許</td>
                <td><table class="layui-table table-striped">
                        @foreach($data['qualification_and_license'] as $item)
                            <tr>
                                <td>名称</td>
                                <td> @if($resumeInfo['license_names'][$item['name']] != 'その他')
                                        {{$resumeInfo['license_names'][$item['name']]}} -
                                        @if($item['name'] == 1)
                                            {{$resumeInfo['license_jlpt'][$item['level']]}}
                                        @else
                                            {{$item['point']}}点
                                        @endif
                                    @else
                                        {{$item['certificate_other']}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>取得年月</td>
                                <td>{{$item['certificate_date']}}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endif
        <tr>
            <td>語学スキル</td>
            <td><table class="layui-table table-striped">
                    <tr>
                        <td>日本語</td>
                        <td>{{$data['jp_level_str']}} </td>
                    </tr>
                    <tr>
                        <td>英語</td>
                        <td>{{$data['en_level_str']}} </td>
                    </tr>
                    @foreach($data['language_skill'] as $item)
                        <tr>
                            <td>{{$item['other_text']}}</td>
                            <td> {{$resumeInfo['other_level'][$item['other_level']]}} </td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td>OS</td>
            <td>{{ $data->skill_os }} </td>
        </tr>
        <tr>
            <td>Office</td>
            <td>{{ $data->skill_office }} </td>
        </tr>
        <tr>
            <td>デザイン(2D/3D)</td>
            <td><table class="layui-table table-striped">
                    <tr>
                        <td>NO</td>
                        <td>グラフィック名</td>
                        <td>使用経験</td>
                    </tr>
                    @foreach($data['it_skill_graphic'] as $key => $item)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>
                            @if($item['name'] != 'その他')
                                {{$item['name']}}
                                @else
                                {{$item['other']}}
                            @endif
                        </td>
                        <td>{{$resumeInfo['residence_in_japan_year'][$item['year']]}} - {{$resumeInfo['residence_in_japan_month'][$item['month']]}}</td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td>開発言語</td>
            <td><table class="layui-table table-striped">
                    <tr>
                        <td>NO</td>
                        <td>開発言語</td>
                        <td>使用経験</td>
                    </tr>
                    @foreach($data['it_skill_language'] as $key => $item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                @if($item['name'] != 'その他')
                                    {{$item['name']}}
                                @else
                                    {{$item['other']}}
                                @endif
                            </td>
                            <td>{{$resumeInfo['residence_in_japan_year'][$item['year']]}} - {{$resumeInfo['residence_in_japan_month'][$item['month']]}}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>

        <tr>
            <td>DB</td>
            <td><table class="layui-table table-striped">
                    <tr>
                        <td>NO</td>
                        <td>DB</td>
                        <td>使用経験</td>
                    </tr>
                    @foreach($data['it_skill_db'] as $key => $item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                @if($item['name'] != 'その他')
                                    {{$item['name']}}
                                @else
                                    {{$item['other']}}
                                @endif
                            </td>
                            <td>{{$resumeInfo['residence_in_japan_year'][$item['year']]}} - {{$resumeInfo['residence_in_japan_month'][$item['month']]}}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td>フレームワーク</td>
            <td><table class="layui-table table-striped">
                    <tr>
                        <th>NO</th>
                        <th>フレームワーク</th>
                        <th>使用経験</th>
                    </tr>
                    @foreach($data['it_skill_framework'] as $key => $item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                @if($item['name'] != 'その他')
                                    {{$item['name']}}
                                @else
                                    {{$item['other']}}
                                @endif
                            </td>
                            <td>{{$resumeInfo['residence_in_japan_year'][$item['year']]}} - {{$resumeInfo['residence_in_japan_month'][$item['month']]}}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>



        <tr>
            <td>通勤時間</td>
            <td>{{$data->commuting_hours_str}} </td>
        </tr>
        <tr>
            <td>扶養家族（配偶者を除く）</td>
            <td>{{$data->family_members_num_str}} </td>
        </tr>

        <tr>
            <td>配偶者</td>
            <td>{{$data->is_spouse_str}} </td>
        </tr>
        <tr>
            <td>配偶者の扶養義務</td>
            <td>{{$data->is_spouse_support_str}} </td>
        </tr>
        <tr>
            <td>希望勤務地</td>
            <td>{{$data->desired_places}}</td>
        </tr>

        <tr>
            <td>志望の動機、自己PR、趣味、特技など</td>
            <td style="white-space: pre-line;">{{$data->pr_other}}</td>
        </tr>

        <tr>
            <td>本人希望記入欄（特に待遇・職種・勤務時間・その他についての希望などがあれば記入）</td>
            <td style="white-space: pre-line;">{{$data->other_expected_types}}</td>
        </tr>

        @if($data['is_experience'])
            <tr>
                <td>職務要約</td>
                <td style="white-space: pre-line;">{{$data->job_summary}}</td>
            </tr>
            <tr>
                <td>職歴</td>
                <td>
                    <table class="layui-table table-striped">
                        @foreach($data['experiences'] as $key => $item)
                            <tr>
                                <th>NO</th>
                                <td>
                                    {{$key+1}}
                                </td>
                                <th>国籍・地域</th>
                                <td>
                                    {{$item['country']}}
                                </td>
                                <th>期間</th>
                                <td>
                                    {{$item['start_date']}} - @if($item['is_now']) 現在に至る @else {{$item['end_date']}} @endif
                                </td>
                                <th>会社名</th>
                                <td>
                                    {{$item['corporate_name']}}
                                </td>
                                <th>部署/役職</th>
                                <td>
                                    {{$item['post_name']}}
                                </td>
                            </tr>
                            <tr>
                                <th>業種</th>
                                <td>
                                    @if($item['industry_name'] != 'その他')
                                        {{$item['industry_name']}}
                                        @else
                                        {{$item['industry_other']}}
                                    @endif
                                </td>
                                <th>従業員数</th>
                                <td>
                                    @if($item['employees_num'] && isset($companyInfo['member_total'][$item['employees_num']]))
                                        {{$companyInfo['member_total'][$item['employees_num']]}}
                                    @else
                                        {{$item['employees_num']}}
                                    @endif
                                </td>
                                <th>雇用形態</th>
                                <td>
                                    {{$item['employ_type']}}
                                </td>
                                <th>職種</th>
                                <td>
                                    @if($item['occupation'] != 'その他')
                                        {{$item['occupation']}}
                                    @else
                                        {{$item['occupation_other']}}
                                    @endif
                                </td>
                                <th>年収</th>
                                <td>
                                    {{$item['annual_income']}}
                                </td>
                            </tr>
                            <tr>
                                <th>担当業務</th>
                                <td colspan="9" style="white-space: pre-line;">
                                    {{$item['undertake_business']}}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endif
        </tbody>
    </table>

    <script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
    <script src="{{asset("/layuiadmin/style/Content/ace/ace.js")}}"></script>
    <script>
        layui.config({
            uriHost: '{{asset("/admin")}}/', //项目管理端path
            base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
        }).extend({
            index: 'lib/index', //主入口模块
            formSelects: 'formSelects-v4.min' //主入口模块
        }).use(['layedit', 'index', 'form', 'upload', 'edit'], function () {
            var $ = layui.$,
                admin = layui.admin,
                layedit = layui.layedit,
                upload = layui.upload,
                form = layui.form;
        })
    </script>
@endsection