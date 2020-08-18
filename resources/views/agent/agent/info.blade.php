@extends('layouts.agent')
@section('content')

    <div class="main">
        <form id="testForm">
            <div class="post_info_body">
                <h3>エージェント会社情報</h3>
                <div class="post_info_content">
                    <ul>
                        @php
                            $target = explode(",", $data->type);
                        @endphp
                        <li class="agent_info_tags_list">
                            @foreach(config("code.agent.type") as $k => $v)
                                @if ($k > 0 && in_array($k, $target))
                                    <span class="agent_info_tags">{{ $v }}</span>
                                @endif
                            @endforeach
                        </li>
                        <li class="agent_info_tags_list">メールアドレス:<b>{{$data['email']}}</b> </li>
                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">所在地（国・地域）<span>＊</span></li>
                                <li class="item_input">
                                    <div class="select">
                                        <select name="nationality_id" id="select4" onchange="select4Change()" required>
                                            <option disabled value="" selected>選択する</option>
                                            @foreach(config("code.resume.nationality_agent") as $k => $v)
                                                @if($k > 0)
                                                    <option @if($data['nationality_id'] == $k) selected @endif value="{{$k}}">{{$v}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                            </ul>

                            <ul class="cols_item">
                                <li class="item_title"></li>
                                <li class="item_input"><input type="text" maxlength="200" class="input_text agent_other" value="{{ $data['nationality'] }}" placeholder="その他" name="nationality" data-max="100" required />
                                </li>
                            </ul>
                        </li>

                        <li class="item_title">会社名<span>＊</span></li>
                        <li class="item_input">
                            <input type="text" class="input_text" maxlength="50" value="{{ $data['agent_name'] }}" name="agent_name" data-max="100" required />
                        </li>
                        <li class="item_title">会社住所<span>＊</span></li>
                        <li class="item_input">
                            <input type="text" class="input_text" value="{{ $data['agent_address'] }}" maxlength="200" name="agent_address" data-max="100" required />
                        </li>
                        <li class="item_title">会社ホームページ</li>
                        <li class="item_input">
                            <input type="url" class="input_text" maxlength="200" value="{{ $data['url'] }}" name="url" placeholder="http://www.xxx.com" data-max="100" />
                        </li>

                        <li class="cols2">
                            <ul class="cols_item">
                                <li class="item_title">担当者名<span>＊</span></li>
                                <li class="item_input"><input type="text" maxlength="50" class="input_text" value="{{ $data['principal_name'] }}" name="principal_name" data-max="100" required />
                                </li>
                            </ul>
                            <ul class="cols_item">
                                <li class="item_title">電話番号<span>＊</span></li>
                                <li class="item_input"><input type="tel" class="input_text" maxlength="200" value="{{ $data['cell_phone'] }}" name="cell_phone" data-max="100" required />
                                </li>
                            </ul>
                        </li>

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
                                window.location.href = "{{ route("agent.index.index") }}";
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
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
            });

            select4Change();
        });

        function select4Change() {
            var text = $("#select4 option:selected").val();
            if (text == 18) {
                $("input[name='nationality']").show();
                $("input[name='nationality']").attr("required", "true");
            } else {
                $("input[name='nationality']").removeAttr("required");
                $("input[name='nationality']").hide();
            }
        }

    </script>

@endsection