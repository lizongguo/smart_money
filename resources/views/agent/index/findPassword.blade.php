@extends('layouts.agent')
@section('content')

    <div class="main">
        <div class="my_setting">
            <div class="my_setting_info">
                <h2>「findjapanjob.com」のパスワードを取得</h2>
            </div>
            <form id="resume_add">
                <input type="hidden" name="hash" value="{{ $hash }}">
                <ul class="my_setting_list">
                    {{--<li>--}}
                        {{--<p>Eメールアドレス:</p>--}}
                        {{--<p><input type="email" class="input_text" value="" name="email" placeholder="" required /></p>--}}
                    {{--</li>--}}
                    <li>
                        <p>新しいパスワード:</p>
                        <p><input type="password" class="input_text" value="" name="password_new" data-min="6" data-max="12" placeholder="" required /></p>
                    </li>
                    <li>
                        <p>新しいパスワード (再入力):</p>
                        <p><input type="password" class="input_text" value="" name="password_new_confirm" data-min="6" data-max="12" placeholder="" required /></p>
                    </li>
                    <li><input type="submit" value="確認" class="login_btn"/></li>
                </ul>
            </form>
        </div>
    </div>

    <script>
        $("#resume_add").html5Validate(function() {
            var email = $("input[name='email']").val();
            var hash = $("input[name='hash']").val();
            var password_new = $("input[name='password_new']").val();
            var password_new_confirm = $("input[name='password_new_confirm']").val();

            if (password_new_confirm != password_new) {
                layer.open({
                    content: "2回入力したパスワードが違います。",
                    skin: 'msg',
                    time: 2
                });
            } else {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
                });
                $.ajax({
                    type: "post",
                    url: "{{ route('agent.index.findPassword') }}",
                    dataType: "json",
                    data: {"email":email, "password_new":password_new, "hash":hash},
                    success: function(content){
                        layer.open({
                            content: content.msg,
                            btn: 'OK',
                            shadeClose: false,
                            yes: function(index) {
                                layer.close(index);
                                if (content.status == 200) {
                                    window.location.href = "{{ route('agent.index.login') }}";
                                }
                            }
                        });
                    },
                    error: function (err){
                        console.log(err);
                    }
                });
            }
        }, {

        });
        //弹框
        $('#popupTrigger2').popup();
        $('#popupTrigger3').popup();
        //复选框,单选框
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });
    </script>

@endsection