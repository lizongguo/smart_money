@extends('layouts.company')
@section('content')

    <div class="main">
        <form id="testForm">
            <div class="post_info_body">
                <h3>会社情報</h3>
                <div class="post_info_content">
                    <ul>
                        <li class="item_title">会社名<span>＊</span></li>
                        <li class="item_input">
                            <input type="text" class="input_text" maxlength="200" value="{{ $data['company_name'] }}" name="company_name" data-max="50" required />
                        </li>
                        <li class="item_title">会社ホームページ</li>
                        <li class="item_input">
                            <input type="url" class="input_text" maxlength="200" value="{{ $data['company_url'] }}" name="company_url" placeholder="http://www.xxx.com" data-max="100" />
                        </li>


                        <li class="item_title">会社住所</li>
                        <li class="item_input">
                            <div class="select">
                                <select name="address_id">
                                    <option value="0" selected>選択する</option>
                                    @foreach(config("code.resume.country_city") as $k => $v)
                                        @if($k > 0)
                                            <option @if($data['address_id'] == $k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                        </li>
                        <li class="item_input">
                            <div class="input_box">
                                <input type="text" value="{{$data['address']}}" maxlength="200" class="input_text" name="address" placeholder="詳細住所"/>
                            </div>
                        </li>
                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">会社設立年月</li>
                                <li class="item_input">

                                    <input type="text" autocomplete="off" readonly class="input_text company_start_date" name="found_date" placeholder="" value="{{ $data['found_date'] }}" data-date-format="yyyy-mm" data-start-view="4" data-min-view="year"/>
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title">資本金</li>
                                <li class="item_input"><input type="text" maxlength="200" class="input_text" value="{{ $data['capital'] }}" name="capital" data-max="100" />
                                </li>
                            </ul>
                        </li>
                        <li class="item_title">社員数</li>
                        <li class="item_input">
                            <div class="select">
                                <select name="member_total">
                                    <option value="0" selected>選択する</option>
                                    @foreach(config("code.company.member_total") as $k => $v)
                                        @if($k > 0)
                                            <option @if($data['member_total'] == $k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </li>

                        <li class="item_title">外国人社員の活躍について</li>
                        <li>
                            <textarea class="text_area" maxlength="2000" name="foreign_member_note">{{ $data['foreign_member_note'] }}</textarea>
                        </li>
                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">業種</li>
                                <li class="item_input">
                                    <div class="select">
                                        <select name="fileds" id="select4" onchange="select4Change()">
                                            <option value="" selected>選択する</option>
                                            @foreach(config("code.resume.desired_fileds") as $k => $v)
                                                @if($k > 0)
                                                    <option @if($data['fileds'] == $k) selected @endif value="{{$k}}">{{$v}}</option>
                                                @endif
                                            @endforeach
                                            <option @if($data['fileds'] == 99) selected @endif value="99">その他</option>
                                        </select>
                                    </div>
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title"></li>
                                <li class="item_input"><input type="text" class="input_text" name="fileds_other" id="company_type_other" value="{{ $data['fileds_other'] }}" placeholder="その他" data-max="100" />
                                </li>
                            </ul>
                        </li>
                        <li class="item_title">会社概要</li>
                        <li><textarea class="text_area" id="company_about" name="company_summary">{{ $data['company_summary'] }}</textarea></li>
                        <li class="item_title">事業内容</li>
                        <li><textarea class="text_area" id="company_content" name="company_bussiness">{{ $data['company_bussiness'] }}</textarea></li>

                        <div class="step_pages_end"><input type="submit" class="resume_ok_btn" value="保存/更新"></div>

                    </ul>
                </div>
            </div>
        </form>
    </div>

    <script>
        $("#testForm").html5Validate(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
            });
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
                            if (content.status == 200) {
                                window.location.href = "{{ route("company.index.index") }}";
                            }
                        }
                    });
                },
                error: function (err){
                    console.log(err);
                }
            });

        }, {
            //novalidate: false
        });

        $(document).ready(function() {
            $('.company_start_date').fdatepicker();
            select4Change();
        });

        function select4Change() {
            var text = $("#select4 option:selected").text();
            if (text == "その他") {
                $("input[name='fileds_other']").show();
                $("input[name='fileds_other']").attr("required", "true");
            } else {
                $("input[name='fileds_other']").removeAttr("required");
                $("input[name='fileds_other']").hide();
            }
        }

    </script>

@endsection